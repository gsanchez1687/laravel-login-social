<?php
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Estableciendo la vista del login por default
Route::get('/', function () {
    return view('auth/login');
});

Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

 
Route::get('/google-auth/callback', function () {
    $userGoogle = Socialite::driver('google')->stateless()->user();
    $user = User::updateOrCreate([
        'google_id'=>$userGoogle->id
    ],
    [
        'name'=>$userGoogle->name,
        'email'=>$userGoogle->email,
    ]);
    Auth::login($user);
    return redirect('/dashboard');
});


Route::get('/facebook-auth/redirect', function () {
    return Socialite::driver('facebook')->redirect();
});

Route::get('/facebook-auth/callback', function () {
    $userFacebook = Socialite::driver('facebook')->stateless()->user();
    $user = User::updateOrCreate([
        'facebook_id'=>$userFacebook->id
    ],
    [
        'name'=>$userFacebook->name,
        'email'=>$userFacebook->email,
    ]);
    Auth::login($user);
    return redirect('/dashboard');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
