<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => LaravelLocalization::setLocale()], function() {
	Route::middleware('checkApiKey')->group(function () {
		Route::match(['post', 'get'], '/send-message', [ApiController::class, 'messageText']);
		Route::match(['post', 'get'], '/send-media', [ApiController::class, 'messageMedia']);
		Route::match(['post', 'get'], '/send-sticker', [ApiController::class, 'messageSticker']);
		Route::match(['post', 'get'], '/send-button', [ApiController::class, 'messageButton']);
		Route::match(['post', 'get'], '/send-template', [ApiController::class, 'messageTemplate']);
		Route::match(['post', 'get'], '/send-list', [ApiController::class, 'messageList']);
		Route::match(['post', 'get'], '/send-poll', [ApiController::class, 'messagePoll']);
		Route::match(['post', 'get'], '/send-location', [ApiController::class, 'messageLocation']);
		Route::match(['post', 'get'], '/send-vcard', [ApiController::class, 'messageVcard']);
		Route::match(['post', 'get'], '/check-number', [ApiController::class, 'checkNumber']);
		Route::post('/logout-device', [DeviceController::class, 'logoutDevice']);
		Route::post('/delete-device', [DeviceController::class, 'deleteDevice']);
	});
	Route::match(['post', 'get'], '/create-user', [ApiController::class, 'createUser']);
	Route::match(['post', 'get'], '/info-user', [ApiController::class, 'infoUser']);
	Route::match(['post', 'get'], '/info-devices', [ApiController::class, 'infoDevices']);
	Route::post('/generate-qr', [ApiController::class, 'generateQr']);
});
?>