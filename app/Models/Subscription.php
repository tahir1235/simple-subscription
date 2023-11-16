<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function user() {
        return $this->belongsTo( \App\Models\User::class, 'user_id', 'id' );
    }

    public function emailSent( $post ) {
        $email = \App\Models\Email::where( 'post_id', $post->id )->where('subscription_id', $this->id )->first();
        return ( $email && $email->status == 1 ) ? true : false;
    }

    public function markEmailSent( $post ) {

        $email = \App\Models\Email::where( 'post_id', $post->id )->where('subscription_id', $this->id )->first();
        
        if( !$email ) {
            $email = new \App\Models\Email();
            $email->post_id = $post->id;
            $email->subscription_id = $this->id;
            $email->status = 0;
        }

        
        if( $email->status != 1 ) {
            $email->status = 1;
            $email->save();
        }
        
    }
}
