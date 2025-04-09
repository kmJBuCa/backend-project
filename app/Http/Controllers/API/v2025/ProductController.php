<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with(['category', 'supplier'])->paginate(10);
            return response()->json([
                'status' => true,
                'data' => $products
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'quantity_in_stock' => 'integer|min:0',
                'minimum_stock_level' => 'integer|min:0',
                'status' => 'in:active,inactive',
                'image' => 'nullable|string',
                'barcode' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'size' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'warranty' => 'nullable|string|max:255',
                'country_of_origin' => 'nullable|string|max:255',
                'supplier_id' => 'required|exists:suppliers,id',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::create($request->all());
            
            return response()->json([
                'status' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Validate if ID is numeric
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid product ID format'
                ], 400);
            }
            
            $product = Product::with(['category', 'supplier'])->findOrFail($id);
            
            return response()->json([
                'status' => true,
                'data' => $product
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'sometimes|string|max:255',
                'cost_price' => 'sometimes|numeric|min:0',
                'selling_price' => 'sometimes|numeric|min:0',
                'quantity_in_stock' => 'sometimes|integer|min:0',
                'minimum_stock_level' => 'sometimes|integer|min:0',
                'status' => 'sometimes|in:active,inactive',
                'image' => 'nullable|string',
                'barcode' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'color' => 'nullable|string|max:255',
                'size' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'warranty' => 'nullable|string|max:255',
                'country_of_origin' => 'nullable|string|max:255',
                'supplier_id' => 'sometimes|exists:suppliers,id',
                'category_id' => 'sometimes|exists:categories,id',
            ]);
            /* 
            Example JSON input for the store method:
            
            {
                "product_name": "Wireless Mouse",
                "cost_price": 15.99,
                "selling_price": 25.99,
                "quantity_in_stock": 100,
                "minimum_stock_level": 10,
                "status": "active",
                "image": "https://example.com/images/wireless-mouse.jpg",
                "barcode": "1234567890123",
                "description": "A high-quality wireless mouse with ergonomic design.",
                "brand": "Logitech",
                "model": "M185",
                "color": "Black",
                "size": "Standard",
                "weight": "0.2kg",
                "dimensions": "10x5x3 cm",
                "warranty": "1 year",
                "country_of_origin": "China",
                "supplier_id": 1,
                "category_id": 2
            }
            */
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($id);
            $product->update($request->all());
            
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /* 
    Example JSON input for the update method:
    
    {
        "product_name": "Wireless Keyboard",
        "cost_price": 20.99,
        "selling_price": 35.99,
        "quantity_in_stock": 50,
        "minimum_stock_level": 5,
        "status": "active",
        "image": "https://example.com/images/wireless-keyboard.jpg",
        "barcode": "9876543210987",
        "description": "A sleek wireless keyboard with long battery life.",
        "brand": "Logitech",
        "model": "K270",
        "color": "White",
        "size": "Full-size",
        "weight": "0.5kg",
        "dimensions": "45x15x2 cm",
        "warranty": "2 years",
        "country_of_origin": "China",
        "supplier_id": 1,
        "category_id": 3
    }
    */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
