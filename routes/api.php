<?php

use App\EthioAdvertPost;
use Illuminate\Http\Request;

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



Route::prefix('/1006616206:AAH8kd8j8mZAyzT4zN4in39addGs3hM603E')->group(function (){
    /* Route::get('/')*/
    Route::post('/', 'TelegramBotApi\IndexController@index');
});
Route::post('/paginated_advert','TelegramBotApi\Api\AdvertPostApi@AdvertiserPosts')->name('PaginatedAdvert');
