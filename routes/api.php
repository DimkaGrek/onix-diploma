<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReviewController;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});
Route::middleware('auth:sanctum')->group(function() {
    Route::get('users/me', [UserController::class, 'show']);
    Route::put('users/me', [UserController::class, 'update']);
    Route::get('/products/{product}/questions', [ProductController::class, 'questions']);
    Route::post('/questions/{question}/answer', [QuestionController::class, 'createAnswer']);
    Route::put('/answers/{answer}', [QuestionController::class, 'updateAnswer']);
    Route::delete('/answers/{answer}', [QuestionController::class, 'deleteAnswer']);
    Route::get('/users/{user}/orders' , [OrderController::class, 'userOrders']);

    // Cart Routes
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart', [CartController::class, 'deleteAll']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy']);

    Route:: apiResources([
        'users' => UserController::class,
        'products' => ProductController::class,
        'categories' => CategoryController::class,
        'reviews' => ReviewController::class,
        'questions' => QuestionController::class,
        'orders' => OrderController::class
    ]);
});
