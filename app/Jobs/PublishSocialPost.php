<?php

namespace App\Jobs;

use App\Models\SocialPost;
use App\Services\SocialMediaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishSocialPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SocialPost $post
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(SocialMediaService $service): void
    {
        Log::info('Publishing social post', ['post_id' => $this->post->id]);

        $results = $service->publishPost($this->post);

        // Check if all platforms succeeded
        $allSucceeded = collect($results)->every(fn($r) => $r['success'] === true);
        $anySucceeded = collect($results)->contains(fn($r) => $r['success'] === true);

        if ($allSucceeded) {
            $this->post->update([
                'status' => 'published',
                'published_at' => now(),
                'error_message' => null,
            ]);
        } elseif ($anySucceeded) {
            // Partial success
            $this->post->update([
                'status' => 'published',
                'published_at' => now(),
                'error_message' => 'Some platforms failed. Check individual post statuses.',
            ]);
        } else {
            // All failed
            $errors = collect($results)
                ->filter(fn($r) => !$r['success'])
                ->map(fn($r) => $r['error'])
                ->implode('; ');

            $this->post->update([
                'status' => 'failed',
                'error_message' => $errors,
                'retry_count' => $this->post->retry_count + 1,
            ]);

            // Throw exception to trigger retry
            throw new \Exception("Failed to publish post: {$errors}");
        }

        Log::info('Social post published', [
            'post_id' => $this->post->id,
            'results' => $results,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Social post publishing failed permanently', [
            'post_id' => $this->post->id,
            'error' => $exception->getMessage(),
        ]);

        $this->post->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
