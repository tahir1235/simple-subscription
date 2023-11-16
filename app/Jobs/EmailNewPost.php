<?php

namespace App\Jobs;

use App\Mail\NewPost;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailNewPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $user;
    protected $subscriber;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $post, $user, $subscriber )
    {
        
        $this->post = $post;
        $this->user = $user;
        $this->subscriber = $subscriber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if( !$this->subscriber->emailSent( $this->post ) ) {
            Mail::to($this->user)->send(new NewPost($this->post));
        }

        $this->subscriber->markEmailSent( $this->post );
    }
}
