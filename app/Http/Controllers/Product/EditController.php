<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditController extends Controller
{
    public function __invoke(Product $product)
    {
        return view('product.edit', compact('product'));
    }
}
