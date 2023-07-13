<?php

use App\Data\ProductData;
use App\Elastics\ElasticSearchBuilder;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {

    // for ($i = 0; $i < 1000; $i++) {
    //     Product::query()->create([
    //         'name' => fake()->name(),
    //         'description' => fake()->sentence(),
    //         'price' => fake()->numberBetween(100_000, 1_000_000),
    //     ]);
    // }
    // $product = Product::query()->create([
    //     'name' => fake()->name(),
    //     'description' => fake()->sentence(),
    //     'price' => 150_000,
    // ]);
    // (new ElasticSearchBuilder)->setModel(new Product())->cleared();
    // $product = Product::query()->delete();
    // $product = Product::query()->first();
    // $product->delete();
    // $product->update([
    //     'name' => 'update',
    //     'description' => fake()->sentence(),
    //     'price' => 150_000,
    // ]);
    // return ProductData::collection(collect((new ElasticSearchBuilder)->setModel(new Product())->search('411501'))->pluck('_source'));
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home/datatable', [App\Http\Controllers\HomeController::class, 'datatable'])->name('home.datatable');
