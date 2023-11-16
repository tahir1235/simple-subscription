<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Models\Website;
use \App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('posts/add', function(Request $request) {

    $title = $request->post('title');
    $description = $request->post('description');

    $website_address = $request->post('website');
    $website = Website::where('address', $website_address)->first();

    if( !$website ) {

        $response = [
            'error' => 'Website does not exist.'
        ];
    } else if( !empty( $title ) && !empty( $description ) ) {

        $post = new Post();
        $post->title = $title;
        $post->description = $description;
        $post->website_id = $website->id;
        $post->save();

        $post->emailToSubscribers();
        
        $response = [
            'success' => 'Post created successfully.'
        ];
    } else {
        $response = [
            'error' => 'Make sure title and description is not empty.'
        ];
    }


    return response()->json( $response );
});


Route::post('subscribe/{user_id}/{website_id}', function(Request $request, $user_id, $website_id ) {
    

    $user = \App\Models\User::find($user_id);
    $website = Website::find( $website_id );


    if( $user && $website && !$user->hasSubscribed( $website ) ) {
        $user->subscribe( $website );
        $response = [
            'success' => 'Successfully subscribed.'
        ];
    } else {
        $response = [
            'error' => 'Failed to subscribe'
        ];
    }


    return response()->json( $response );

})
->where(['user_id', '[0-9]+', 'website_id', '[0-9]+']);