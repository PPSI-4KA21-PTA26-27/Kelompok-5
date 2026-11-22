<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->get();

        $products = Product::with(['variants', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('banners', 'products', 'categories'));
    }

    public function show($slug)
    {
        // Mencari produk berdasarkan slug beserta varian dan kategorinya
        $product = Product::with(['variants', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('detail', compact('product'));
    }

    public function catalog(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $activeCategory = null;

        $query = Product::with(['variants', 'category']);

        // Filter berdasarkan slug kategori (?category=pashmina)
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->category)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        // Pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pengurutan produk
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        // 8 produk per halaman (2 baris x 4 kolom)
        $products = $query->paginate(8)->withQueryString();

        return view('catalog', compact('products', 'categories', 'activeCategory'));
    }

    public function bestSeller()
    {
        $bestSellers = Product::with(['variants', 'category'])
            ->take(4)
            ->get();

        return view('best-seller', compact('bestSellers'));
    }
}