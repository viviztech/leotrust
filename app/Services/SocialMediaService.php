<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\SocialPost;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialMediaService
{
    /**
     * Publish a post to all connected accounts.
     */
    public function publishPost(SocialPost $post): array
    {
        $results = [];

        foreach ($post->socialAccounts as $account) {
            try {
                $result = match ($account->provider_name) {
                    'facebook' => $this->publishToFacebook($account, $post),
                    'twitter' => $this->publishToTwitter($account, $post),
                    'linkedin' => $this->publishToLinkedIn($account, $post),
                    default => throw new \Exception("Unsupported provider: {$account->provider_name}"),
                };

                // Update pivot table with success
                $post->socialAccounts()->updateExistingPivot($account->id, [
                    'platform_post_id' => $result['post_id'] ?? null,
                    'platform_post_url' => $result['post_url'] ?? null,
                    'status' => 'published',
                    'published_at' => now(),
                ]);

                $account->update(['last_used_at' => now()]);

                $results[$account->id] = ['success' => true, 'result' => $result];

            } catch (\Exception $e) {
                Log::error("Failed to publish to {$account->provider_name}", [
                    'account_id' => $account->id,
                    'post_id' => $post->id,
                    'error' => $e->getMessage(),
                ]);

                // Update pivot table with failure
                $post->socialAccounts()->updateExistingPivot($account->id, [
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $results[$account->id] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Publish to Facebook.
     */
    protected function publishToFacebook(SocialAccount $account, SocialPost $post): array
    {
        $accessToken = $account->access_token;
        $pageId = $account->provider_id;

        $data = ['message' => $post->content];

        // If there's an image, upload it
        if ($post->image_path) {
            $imagePath = storage_path('app/public/' . $post->image_path);

            if (file_exists($imagePath)) {
                $response = Http::attach(
                    'source',
                    file_get_contents($imagePath),
                    basename($imagePath)
                )->post("https://graph.facebook.com/v18.0/{$pageId}/photos", [
                            'caption' => $post->content,
                            'access_token' => $accessToken,
                        ]);
            } else {
                $response = Http::post("https://graph.facebook.com/v18.0/{$pageId}/feed", array_merge($data, [
                    'access_token' => $accessToken,
                ]));
            }
        } else {
            $response = Http::post("https://graph.facebook.com/v18.0/{$pageId}/feed", array_merge($data, [
                'access_token' => $accessToken,
            ]));
        }

        if (!$response->successful()) {
            throw new \Exception($response->json('error.message', 'Failed to post to Facebook'));
        }

        $postId = $response->json('id') ?? $response->json('post_id');

        return [
            'post_id' => $postId,
            'post_url' => "https://facebook.com/{$postId}",
        ];
    }

    /**
     * Publish to Twitter/X.
     */
    protected function publishToTwitter(SocialAccount $account, SocialPost $post): array
    {
        $connection = new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret'),
            $account->access_token,
            $account->refresh_token
        );

        $connection->setApiVersion('2');

        // Truncate content to 280 characters
        $content = mb_strlen($post->content) > 280
            ? mb_substr($post->content, 0, 277) . '...'
            : $post->content;

        $data = ['text' => $content];

        // Upload media if present
        if ($post->image_path) {
            $imagePath = storage_path('app/public/' . $post->image_path);

            if (file_exists($imagePath)) {
                $connection->setApiVersion('1.1');
                $media = $connection->upload('media/upload', ['media' => $imagePath]);
                $connection->setApiVersion('2');

                if (isset($media->media_id_string)) {
                    $data['media'] = ['media_ids' => [$media->media_id_string]];
                }
            }
        }

        $result = $connection->post('tweets', $data, true);

        if (isset($result->errors)) {
            throw new \Exception($result->errors[0]->message ?? 'Failed to post to Twitter');
        }

        return [
            'post_id' => $result->data->id ?? null,
            'post_url' => $result->data->id ? "https://twitter.com/i/web/status/{$result->data->id}" : null,
        ];
    }

    /**
     * Publish to LinkedIn.
     */
    protected function publishToLinkedIn(SocialAccount $account, SocialPost $post): array
    {
        $accessToken = $account->access_token;
        $personUrn = "urn:li:person:{$account->provider_id}";

        $postData = [
            'author' => $personUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $post->content,
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        $response = Http::withToken($accessToken)
            ->post('https://api.linkedin.com/v2/ugcPosts', $postData);

        if (!$response->successful()) {
            throw new \Exception($response->json('message', 'Failed to post to LinkedIn'));
        }

        $postId = $response->json('id');

        return [
            'post_id' => $postId,
            'post_url' => null, // LinkedIn doesn't return a direct URL
        ];
    }

    /**
     * Refresh an expired token.
     */
    public function refreshToken(SocialAccount $account): bool
    {
        try {
            $result = match ($account->provider_name) {
                'facebook' => $this->refreshFacebookToken($account),
                'linkedin' => $this->refreshLinkedInToken($account),
                default => false,
            };

            return $result;

        } catch (\Exception $e) {
            Log::error("Failed to refresh token for {$account->provider_name}", [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Refresh Facebook long-lived token.
     */
    protected function refreshFacebookToken(SocialAccount $account): bool
    {
        $response = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
            'grant_type' => 'fb_exchange_token',
            'client_id' => config('services.facebook.client_id'),
            'client_secret' => config('services.facebook.client_secret'),
            'fb_exchange_token' => $account->access_token,
        ]);

        if ($response->successful()) {
            $account->update([
                'access_token' => $response->json('access_token'),
                'token_expires_at' => now()->addSeconds($response->json('expires_in', 5184000)),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Refresh LinkedIn token.
     */
    protected function refreshLinkedInToken(SocialAccount $account): bool
    {
        if (!$account->refresh_token) {
            return false;
        }

        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $account->refresh_token,
            'client_id' => config('services.linkedin.client_id'),
            'client_secret' => config('services.linkedin.client_secret'),
        ]);

        if ($response->successful()) {
            $account->update([
                'access_token' => $response->json('access_token'),
                'refresh_token' => $response->json('refresh_token'),
                'token_expires_at' => now()->addSeconds($response->json('expires_in')),
            ]);
            return true;
        }

        return false;
    }
}
