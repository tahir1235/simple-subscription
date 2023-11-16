<?php

namespace App\Console\Commands;

use App\Models\Email;
use Illuminate\Console\Command;

class emailSubscriber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email to website subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {





        // Lets consider last 5 posts are new posts

        $posts = \App\Models\Post::orderBy('created_at', 'desc')->limit(5)->get();

        


        //dd( $posts );
        
        
        foreach( $posts as $post ) {
            foreach( $post->website->subscribers as $subscriber ) {
                if( !$subscriber->emailSent( $post ) ) {
                    \App\Jobs\EmailNewPost::dispatch( $post, $subscriber->user, $subscriber );
                }
            }
        }
    }
}
