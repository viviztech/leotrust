<?php

namespace App\Console\Commands;

use App\Jobs\PublishSocialPost;
use App\Models\SocialPost;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'social:publish-scheduled';

    /**
     * The console command description.
     */
    protected $description = 'Publish all social media posts that are scheduled for now or earlier';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $posts = SocialPost::readyToPublish()->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish.');
            return self::SUCCESS;
        }

        $this->info("Found {$posts->count()} posts to publish.");

        foreach ($posts as $post) {
            $post->update(['status' => 'publishing']);
            PublishSocialPost::dispatch($post);
            $this->line("Dispatched post #{$post->id} for publishing.");
        }

        $this->info('All posts have been dispatched to the queue.');

        return self::SUCCESS;
    }
}
