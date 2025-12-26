<?php

use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\WebhookController;
use App\Models\Donation;
use App\Services\DonationService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/campaigns', [PageController::class, 'campaigns'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [PageController::class, 'campaignShow'])->name('campaigns.show');
Route::get('/stories', [PageController::class, 'stories'])->name('stories');
Route::get('/donate', [PageController::class, 'donate'])->name('donate');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Social Media OAuth Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('auth')->name('social.auth.')->group(function () {
    Route::get('{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('redirect');
    Route::get('{provider}/callback', [SocialAuthController::class, 'callback'])->name('callback');
});

/*
|--------------------------------------------------------------------------
| Webhook Routes (No CSRF)
|--------------------------------------------------------------------------
*/
Route::prefix('webhooks')->withoutMiddleware(['web'])->group(function () {
    Route::post('stripe', [WebhookController::class, 'handleStripe'])->name('webhooks.stripe');
    Route::post('razorpay', [WebhookController::class, 'handleRazorpay'])->name('webhooks.razorpay');
});

/*
|--------------------------------------------------------------------------
| Donation Receipt Routes
|--------------------------------------------------------------------------
*/
Route::get('donations/{donation}/receipt', function (Donation $donation, DonationService $service) {
    if ($donation->status !== 'completed') {
        abort(404, 'Receipt not available');
    }

    $receiptUrl = $service->generateReceipt($donation);
    return redirect($receiptUrl);
})->name('donations.receipt');


