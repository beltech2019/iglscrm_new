<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\SocialTicketController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/replyTweetId', [TweetController::class, 'replyTweetId']);
Route::get('/getSocialTicket', [SocialTicketController::class, 'getSocialTicket']);
Route::post('/generateTicket', [SocialTicketController::class, 'generateTicket']);
Route::post('/postTweet/{id}', [TweetController::class, 'postTweet']);
Route::get('/deleteTweet', [TweetController::class, 'deleteTweet']);
Route::get('/getTweet', [TweetController::class, 'getTweet']);
Route::get('/getDemoTweet', [TweetController::class, 'getDemoTweet']);
Route::get('/getDemoTweetAgain', [TweetController::class, 'getDemoTweetAgain']);
Route::post('/getPostBywhatsapp', [TweetController::class, 'getPostBywhatsapp']);
Route::get('/getPostBywhatsapp', [TweetController::class, 'getPostBywhatsapp']);
Route::get('/getPostByfacebook', [TweetController::class, 'getPostByfacebook']);
Route::post('/getPostByfacebook', [TweetController::class, 'getPostByfacebook']);
Route::get('/getPostInstagram', [TweetController::class, 'getPostInstagram']);
Route::post('/getPostInstagram', [TweetController::class, 'getPostInstagram']);
Route::get('/getPostByLinkedin', [TweetController::class, 'getPostByLinkedin']);
Route::post('/getPostByLinkedin', [TweetController::class, 'getPostByLinkedin']);
Route::post('/replyDMByTwitter', [TweetController::class, 'replyDMByTwitter']);
Route::post('/handleWebhook', [WebhookController::class, 'handleWebhook']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
