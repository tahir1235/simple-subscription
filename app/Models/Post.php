<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'website_id'];



    public function website() {
        
        return $this->hasOne( \App\Models\Website::class, 'id', 'website_id' );
        
    }

    public function emailToSubscribers() {

        $website = \App\Models\Website::find( $this->website_id );

        if( !$website ) {
            return;
        }


        $subscribers = $website->subscribers;

        foreach( $subscribers as $subscriber ) {
            \App\Jobs\EmailNewPost::dispatch( $this, $subscriber->user, $subscriber );
        }
        
    }
}
