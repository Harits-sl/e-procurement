<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vendor = Vendor::where('user_id', $request->user()->id)->first();
        $products = Product::where('vendor_id', $vendor->id)->get();

        $productResource =  new ProductResource(true, 'List Data Products', $products);
        return $productResource->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $vendor = Vendor::where('user_id', $request->user()->id)->first();

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->image;
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $imageName);
        }

        $product = Product::create([
            'vendor_id' => $vendor->id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
        ]);

        $productResource =  new ProductResource(true, 'Data Product Created', $product);
        return $productResource->response()->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $product = $this->vendorProduct($request->user()->id, $id);
        if ($product != null) {
            $productResource =  new ProductResource(true, 'Data Product', $product);
            return $productResource->response()->setStatusCode(200);
        } else {
            $productResource =  new ProductResource(false, 'Product Not Found', $product);
            return $productResource->response()->setStatusCode(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->vendorProduct($request->user()->id, $id);
        if ($product != null) {
            // Handle image upload
            $imageName = $product->image;
            if ($request->hasFile('image')) {
                $file = $request->image;
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/products'), $imageName);

                // delete old image
                File::delete(public_path('images/products/' . $product->image));
            }

            $product->update([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $imageName
            ]);

            $productResource =  new ProductResource(true, 'Data Product Edited', $product);
            return $productResource->response()->setStatusCode(200);
        } else {
            $productResource =  new ProductResource(false, 'Product Not Found', $product);
            return $productResource->response()->setStatusCode(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $product = $this->vendorProduct($request->user()->id, $id);
        if ($product != null) {
            $product->delete();
            $productResource =  new ProductResource(true, 'Data Product Deleted', []);
            return $productResource->response()->setStatusCode(200);
        } else {
            $productResource =  new ProductResource(false, 'Product Not Found', $product);
            return $productResource->response()->setStatusCode(404);
        }
    }

    // according to the vendor who has the product id
    private function vendorProduct($userId, $productId)
    {
        $vendor = Vendor::where('user_id', $userId)->first();
        $product = Product::where('vendor_id', $vendor->id)->where('id', $productId)->first();
        if ($product != null) {
            return $product;
        } else {
            return null;
        }
    }
}
