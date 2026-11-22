<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string|max:100',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Simpan Gambar Utama Product (Cover)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 2. Buat Data Produk dengan Slug Unik Otomatis
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = Product::where('slug', $baseSlug)->exists()
            ? $baseSlug . '-' . strtolower(Str::random(4))
            : $baseSlug;

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $uniqueSlug,
            'price' => $request->price,
            'weight' => $request->weight,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // 3. Simpan Varian Warna, Ukuran, & SKU Otomatis
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $variantImagePath = null;
                if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImagePath = $variantData['image']->store('variants', 'public');
                }

                $generatedSku = 'SD-' . $product->id . '-' . strtoupper(Str::slug($variantData['color'])) . '-' . strtoupper(Str::random(4));

                $product->variants()->create([
                    'sku'   => $generatedSku,
                    'color' => $variantData['color'],
                    'size'  => $variantData['size'] ?? null,
                    'stock' => $variantData['stock'],
                    'image' => $variantImagePath,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk dan varian warna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('variants')->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string|max:100',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Update Gambar Utama Produk jika ada file baru
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // 2. Update Informasi Produk
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = Product::where('slug', $baseSlug)->where('id', '!=', $product->id)->exists()
            ? $baseSlug . '-' . strtolower(Str::random(4))
            : $baseSlug;

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $uniqueSlug,
            'price' => $request->price,
            'weight' => $request->weight,
            'description' => $request->description,
            'image' => $product->image,
        ]);

        // 3. Kelola Varian Warna & Ukuran
        $submittedVariantIds = [];

        foreach ($request->variants as $variantData) {
            if (isset($variantData['id'])) {
                $variant = ProductVariant::find($variantData['id']);

                if ($variant) {
                    $variantImagePath = $variant->image;

                    if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $variantImagePath = $variantData['image']->store('variants', 'public');
                    }

                    $variant->update([
                        'color' => $variantData['color'],
                        'size'  => $variantData['size'] ?? null,
                        'stock' => $variantData['stock'],
                        'image' => $variantImagePath,
                    ]);

                    $submittedVariantIds[] = $variant->id;
                }
            } else {
                $variantImagePath = null;
                if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImagePath = $variantData['image']->store('variants', 'public');
                }

                $generatedSku = 'SD-' . $product->id . '-' . strtoupper(Str::slug($variantData['color'])) . '-' . strtoupper(Str::random(4));

                $newVariant = $product->variants()->create([
                    'sku'   => $generatedSku,
                    'color' => $variantData['color'],
                    'size'  => $variantData['size'] ?? null,
                    'stock' => $variantData['stock'],
                    'image' => $variantImagePath,
                ]);

                $submittedVariantIds[] = $newVariant->id;
            }
        }

        // Hapus varian lama yang dihapus dari form edit
        $deletedVariants = $product->variants()->whereNotIn('id', $submittedVariantIds)->get();
        foreach ($deletedVariants as $deletedVariant) {
            if ($deletedVariant->image && Storage::disk('public')->exists($deletedVariant->image)) {
                Storage::disk('public')->delete($deletedVariant->image);
            }
            $deletedVariant->delete();
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk dan varian warna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::with('variants')->findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->variants as $variant) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}