<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug/packages', function() {
    $packages = App\Models\InvestmentPackage::active()->get();
    return view('debug.packages', compact('packages'));
});
