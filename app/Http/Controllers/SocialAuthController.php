<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported providers.
     */
    protected array $providers = ['facebook', 'twitter', 'linkedin'];

    /**
     * Redirect to OAuth provider.
     */
    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404, 'Provider not supported');
        }

        $scopes = match ($provider) {
            'facebook' => ['pages_manage_posts', 'pages_read_engagement'],
            'twitter' => ['tweet.read', 'tweet.write', 'users.read', 'offline.access'],
            'linkedin' => ['w_member_social', 'r_liteprofile'],
            default => [],
        };

        return Socialite::driver($provider)
            ->scopes($scopes)
            ->redirect();
    }

    /**
     * Handle OAuth callback.
     */
    public function callback(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404, 'Provider not supported');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Update or create social account
            $account = SocialAccount::updateOrCreate(
                [
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                ],
                [
                    'account_name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown',
                    'account_username' => $socialUser->getNickname(),
                    'account_avatar' => $socialUser->getAvatar(),
                    'access_token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken ?? null,
                    'token_expires_at' => $socialUser->expiresIn
                        ? now()->addSeconds($socialUser->expiresIn)
                        : null,
                    'user_id' => Auth::id(),
                    'is_active' => true,
                    'metadata' => [
                        'raw' => $socialUser->getRaw(),
                    ],
                ]
            );

            return redirect()
                ->route('filament.admin.resources.social-accounts.index')
                ->with('success', "Connected to {$provider} successfully!");

        } catch (\Exception $e) {
            return redirect()
                ->route('filament.admin.resources.social-accounts.index')
                ->with('error', "Failed to connect to {$provider}: " . $e->getMessage());
        }
    }
}
