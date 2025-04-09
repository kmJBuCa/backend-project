<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $suppliers = Supplier::all();
            return response()->json([
                'status' => 'success',
                'data' => $suppliers,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve suppliers',
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
                'supplier_name' => 'required|string|max:100',
                'contact_person' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:100|unique:suppliers',
                'website' => 'nullable|string|max:255|url',
                'bio' => 'nullable|string',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:50',
                'country' => 'required|string|max:50',
                'brand_name' => 'required|string|max:100',
                'active' => 'boolean',
                'bank_name' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'bank_account_name' => 'nullable|string|max:255',
                'logo' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $supplier = Supplier::create($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier created successfully',
                'data' => $supplier
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }
/* 
input example json:
by url http://localhost:8000/api/v1/suppliers 
{
    "supplier_name": "ABC Supplies",
    "contact_person": "John Doe",
    "phone": "+1234567890",
    "email": "contact@abcsupplies.com",
    "website": "https://www.abcsupplies.com",
    "bio": "Leading supplier of office equipment.",
    "address": "123 Main Street",
    "city": "Metropolis",
    "country": "USA",
    "brand_name": "ABC",
    "active": true,
    "bank_name": "Bank of America",
    "bank_account_number": "123456789",
    "bank_account_name": "ABC Supplies Inc.",
    "logo": "https://www.abcsupplies.com/logo.png"
}
 */
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $supplier
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Supplier not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'supplier_name' => 'string|max:100',
                'contact_person' => 'string|max:100',
                'phone' => 'string|max:20',
                'email' => 'email|max:100|unique:suppliers,email,' . $id,
                'website' => 'nullable|string|max:255|url',
                'bio' => 'nullable|string',
                'address' => 'string|max:255',
                'city' => 'string|max:50',
                'country' => 'string|max:50',
                'brand_name' => 'string|max:100',
                'active' => 'boolean',
                'bank_name' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
                'bank_account_name' => 'nullable|string|max:255',
                'logo' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $supplier->update($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier updated successfully',
                'data' => $supplier
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update supplier',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    /* 
    Example JSON input for the update method:
    
    {
        "supplier_name": "XYZ Supplies",
        "contact_person": "Jane Doe",
        "phone": "+9876543210",
        "email": "contact@xyzsupplies.com",
        "website": "https://www.xyzsupplies.com",
        "bio": "Top supplier of industrial equipment.",
        "address": "456 Elm Street",
        "city": "Gotham",
        "country": "USA",
        "brand_name": "XYZ",
        "active": false,
        "bank_name": "Chase Bank",
        "bank_account_number": "987654321",
        "bank_account_name": "XYZ Supplies LLC",
        "logo": "https://www.xyzsupplies.com/logo.png"
    }
    */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete supplier',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
}
