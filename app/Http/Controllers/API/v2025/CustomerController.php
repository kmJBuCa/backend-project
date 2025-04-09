<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json([
                'status' => 'success',
                'data' => $customers
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch customers',
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
                'customer_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'required|email|unique:customers,email',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'zip' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'company' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
                'customer_type' => 'nullable|string|in:regular,vip,premium',
                'bank_name' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
            ]);
/* 
{
    "customer_name": "Phdea",
    "contact_name": "Chea",
    "phone": "+9876543210",
    "email": "a23@example.com",
    "address": "456 Elm Street",
    "city": "Gotham",
    "state": "California",
    "zip": "90210",
    "country": "USA",
    "company": "Doe Ventures",
    "website": "https://www.doeventures.com",
    "status": "inactive",
    "customer_type": "premium",
    "bank_name": "Chase Bank",
    "account_name": "Johnathan Doe",
    "account_number": "987654321",
    "notes": "Customer requested account update."
}

 */
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer = Customer::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'data' => $customer
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create customer',
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
            $customer = Customer::with('orders')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $customer
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
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
            $customer = Customer::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'customer_name' => 'sometimes|required|string|max:255',
                'contact_name' => 'sometimes|required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'sometimes|required|email|unique:customers,email,'.$id,
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'zip' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'company' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
                'customer_type' => 'nullable|string|in:regular,vip,premium',
                'bank_name' => 'nullable|string|max:255',
                'account_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
            ]);
/* 
{
    "customer_name": "Johnathan Doe",
    "contact_name": "Jane Smith",
    "phone": "+9876543210",
    "email": "johnathan.doe@example.com",
    "address": "456 Elm Street",
    "city": "Gotham",
    "state": "California",
    "zip": "90210",
    "country": "USA",
    "company": "Doe Ventures",
    "website": "https://www.doeventures.com",
    "status": "inactive",
    "customer_type": "premium",
    "bank_name": "Chase Bank",
    "account_name": "Johnathan Doe",
    "account_number": "987654321",
    "notes": "Customer requested account update."
}

*/
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully',
                'data' => $customer
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
