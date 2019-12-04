<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProducts($id)
    {
        $products = Product::where('category_id', $id)->get();

        return response()->json($products, 200);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'q' => 'required'
        ]);

        $item = $request->q;

        $prods = Product::where('name', 'like', "%".$item."%")->orWhere('description', 'like', "%".$item."%")
                        ->orWhereHas('service', function($q) use($item){
                            $q->where('name', 'like', "%".$item."%")->orWhere('description', 'like', "%".$item."%");
                            })
                        ->get();

        return response()->json($prods, 200);
    }

    public function getSingleProduct($id)
    {
        $product = Product::findOrFail($id);

        $img = $product->picture;

        $filePath = '/products/' . $img;

        $imgUrl = Storage::disk('s3')->url($filePath);

        return response()->json(['product' => $product, 'image' => $imgUrl], 200);
    }

    public function getSimilarProducts($cat, $id)
    {
        $prods = Product::where('category_id', $cat)->where('id', '!=', $id)->inRandomOrder()->limit(3)->get();
        
        foreach ($prods as $prod) {
            $img = $prod->picture;
            $filepath = '/products/' . $img;
            $imgUrl = Storage::disk('s3')->url($filepath);
            $prod->img = $imgUrl;
        }
        return response()->json($prods, 200);
    }

    public function getTestProducts()
    {
        $cat = 13;
        $prods = Product::where('category_id', $cat)->get();

        return response()->json($prods, 200);
    }

    public function getProductsByCats($id)
    {
        $prods = Product::where('category_id', $id)->get();

        return response()->json($prods, 200);
    }

    public function getProductS3Image($id)
    {
        $prod = Product::findOrFail($id);
        $img = $prod->picture;

        $filePath = '/products/' . $img;
        $imgUrl = Storage::disk('s3')->url($filePath);

        return response()->json($imgUrl, 200);
    }
}
