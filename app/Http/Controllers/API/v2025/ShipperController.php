<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipper;
use Illuminate\Support\Facades\Validator;
use Exception;

class ShipperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $shippers = Shipper::all();
            return response()->json([
                'status' => 'success',
                'data' => $shippers
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve shippers',
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
                'shipper_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'shipping_methods' => 'nullable|json',
                'email' => 'nullable|email|max:255',
                'notes' => 'nullable|string',
            ]);
/* 
{
    "shipper_name": "Fast Delivery Co.",
    "contact_person": "John Smith",
    "phone": "+1234567890",
    "address": "123 Main Street, Metropolis",
    "shipping_methods": "{\"standard\": true, \"express\": true}",
    "email": "contact@fastdelivery.com",
    "notes": "Specializes in same-day delivery."
}

*/
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $shipper = Shipper::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Shipper created successfully',
                'data' => $shipper
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create shipper',
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
            $shipper = Shipper::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $shipper
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shipper not found',
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
            $validator = Validator::make($request->all(), [
                'shipper_name' => 'sometimes|required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'sometimes|required|string|max:20',
                'address' => 'nullable|string',
                'shipping_methods' => 'nullable|json',
                'email' => 'nullable|email|max:255',
                'notes' => 'nullable|string',
            ]);
/* 
{
    "shipper_name": "Quick Ship Inc.",
    "contact_person": "Jane Doe",
    "phone": "+9876543210",
    "address": "456 Elm Street, Gotham",
    "shipping_methods": "{\"standard\": true, \"overnight\": true}",
    "email": "support@quickship.com",
    "notes": "Offers overnight shipping services."
}

*/
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $shipper = Shipper::findOrFail($id);
            $shipper->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Shipper updated successfully',
                'data' => $shipper
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update shipper',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $shipper = Shipper::findOrFail($id);
            $shipper->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Shipper deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete shipper',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
