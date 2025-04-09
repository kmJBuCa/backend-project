<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = Employee::with('orders')->get();
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employees',
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
            // Check if this is a JSON request
            if ($request->isJson() || !$request->hasFile('photo')) {
                // For JSON requests, we initially skip photo validation
                $rules = [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'position' => 'required|string',
                    'department' => 'required|string',
                    'hire_date' => 'required|date',
                    'phone' => 'nullable|string',
                    'email' => 'required|email|unique:employees,email',
                    'address' => 'nullable|string',
                    'gender' => 'nullable|in:male,female,other',
                    'photo' => 'nullable|string', // Allow photo as string for JSON requests
                ];
            } else {
                // For multipart form requests with files
                $rules = [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'position' => 'required|string',
                    'department' => 'required|string',
                    'hire_date' => 'required|date',
                    'phone' => 'nullable|string',
                    'email' => 'required|email|unique:employees,email',
                    'address' => 'nullable|string',
                    'gender' => 'nullable|in:male,female,other',
                    'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Keeping the 2MB limit for images
                ];
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('employee_photos', 'public');
                $data['photo'] = $path;
            } elseif (isset($data['photo']) && is_string($data['photo']) && !empty($data['photo'])) {
                // If photo is sent as a string (e.g., base64 or URL), handle accordingly
                // For simplicity, we're just keeping the string value, but you might want to
                // download from URL or decode base64 depending on your requirements
                // $data['photo'] remains as is
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('employee_photos', 'public');
                $data['photo'] = $path;
            }

            $employee = Employee::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully',
                'data' => $employee
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }
/*

create 

{
    "first_name": "Vi",
    "last_name": "Doe",
    "position": "Engineer",
    "department": "IT",
    "hire_date": "2025-03-01",
    "phone": "+1234567890",
    "email": "f24s75@example.com",
    "address": "123 Main Street, Metropolis",
    "gender": "male",
    "photo": "employee_photo.jpg"
}


*/
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $employee = Employee::with('orders')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $employee
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employee',
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
            $employee = Employee::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'position' => 'sometimes|required|string|max:255',
                'department' => 'sometimes|required|string|max:255',
                'hire_date' => 'sometimes|required|date',
                'phone' => 'nullable|string|max:20',
                'email' => 'sometimes|required|email|unique:employees,email,' . $id,
                'address' => 'nullable|string',
                'gender' => 'nullable|in:male,female,other',
                'photo' => 'nullable|string', // Allow photo as string for JSON requests
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
/* 
{
    "first_name": "John",
    "last_name": "Doe",
    "position": "Software Engineer",
    "department": "IT",
    "hire_date": "2023-03-01",
    "phone": "+1234567890",
    "email": "johsssndoe@example.com",
    "address": "123 Main Street, Metropolis",
    "gender": "male",
    "photo": "employdee_photo.jpg"
}

*/
            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $path = $request->file('photo')->store('employee_photos', 'public');
                $data['photo'] = $path;
            }

            $employee->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'data' => $employee
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            // Delete employee photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            
            $employee->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
