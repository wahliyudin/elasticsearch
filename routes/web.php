<?php

use App\Models\Product;
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

    // $product = Product::query()->create([
    //     'name' => fake()->name(),
    //     'description' => fake()->sentence(),
    //     'price' => 150_000,
    // ]);


    // $product = Product::query()->delete();

    // $product->delete();
    // $product->update([
    //     'name' => 'update',
    //     'description' => fake()->sentence(),
    //     'price' => 150_000,
    // ]);

    // (new Product())->cleared();

    return view('welcome');
});
