<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('events')->get();
        return view('categories.index', compact('categories'));
    }

    public function show($id)
    {
        $category = Category::with('events')->findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function apiIndex()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}