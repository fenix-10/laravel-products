<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
