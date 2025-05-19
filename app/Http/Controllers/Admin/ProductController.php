<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function dialogCrud(Request $request)
    {

        $products = Product::when($request->productFilter && $request->productFilter !== 'all', function ($query) use ($request) {
            $query->where('status', $request->productFilter);
        })
            ->when($request->search && $request->search !== 'all', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(function ($product) {
                return [
                    ...$product->toArray(),
                    'created_at' => $product->created_at->format('Y-m-d'),
                ];
            });

        $productFilter = $request->get('productFilter') ?? '';
        $search = $request->get('search') ?? '';

        return inertia('Admin/Products/dialog_crud', compact('products', 'productFilter', 'search'));
    }

    public function drawerCrud(Request $request)
    {
        $products = Product::when($request->productFilter && $request->productFilter !== 'all', function ($query) use ($request) {
            $query->where('status', $request->productFilter);
        })
            ->when($request->search && $request->search !== 'all', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(function ($product) {
                return [
                    ...$product->toArray(),
                    'created_at' => $product->created_at->format('Y-m-d'),
                ];
            });

        $productFilter = $request->get('productFilter') ?? '';
        $search = $request->get('search') ?? '';

        return inertia('Admin/Products/drawer_crud', compact('products', 'productFilter', 'search'));
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required',
            'status' => 'required|string|max:255',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;

        $productName = Str::slug($request->name);

        if ($request->has('image') && $request->image !== null) {
            $product->image = FileService::moveTempFile($request->image, "products/{$productName}", $productName.'-image');
        }
        $product->save();

        return '';
    }

    public function update(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => '',
            'status' => 'required|string|max:255',
        ]);

        $product = Product::find($request->id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;

        if ($request->has('image') && $request->image !== null) {
            $product->image = FileService::moveTempFile($request->image, "products/{$product->name}", $product->name.'-image');
        }
        $product->save();

        return '';
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);

        if ($product->image) {
            // Get the directory path
            $imagePath = str_replace('storage/', '', $product->image);
            $imageDir = dirname($imagePath);

            // Delete the file first
            Storage::disk('public')->delete($imagePath);

            // Then delete the directory if it's empty
            if (Storage::disk('public')->exists($imageDir) && count(Storage::disk('public')->files($imageDir)) === 0) {
                Storage::disk('public')->deleteDirectory($imageDir);
            }
        }

        $product->delete();

        return '';
    }
}
