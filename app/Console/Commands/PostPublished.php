<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Notifications\PostPublishedNotification;
use Illuminate\Console\Command;

class PostPublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:post-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the posts that were published today
        $posts = Post::whereDate('published_at',"<=", date("Y-m-d"))->scheduled()->get();

        foreach ($posts as $post) {
            $post->update([
                'status' => 1
            ]);
            $post->user->notify(new PostPublishedNotification($post));
        }

        $this->info('Users notified about newly published posts.');
    }
}
