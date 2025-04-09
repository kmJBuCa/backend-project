<?php

namespace App\Http\Controllers\API\v2025;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipper;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $orders = Order::with(['customer', 'shipper', 'employee', 'orderDetails.product'])
                ->latest()
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => [
                    'orders' => $orders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    



    public function store(OrderStoreRequest $request): JsonResponse
    {
        // Validate request data (handled by OrderStoreRequest)
        $validated = $request->validated();
    
        // Begin transaction for order creation
        DB::beginTransaction();
    
        try {
            // Retrieve customer, employee, and shipper details
            $customer = Customer::findOrFail($validated['customer_id']);
            $employee = Employee::findOrFail($validated['employee_id']);
            $shipper = Shipper::findOrFail($validated['shipper_id']);
    
            // Initialize total amount
            $totalAmount = 0;
    
            // Create order
            $order = Order::create([
                'order_date' => $validated['order_date'],
                'customer_id' => $validated['customer_id'],
                'employee_id' => $validated['employee_id'],
                'shipper_id' => $validated['shipper_id'],
                'total_amount' => 0, // Will be updated after calculating item totals
            ]);
    
            // Process order items
            $stockUpdates = [];
            $orderItems = [];
    
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
    
                // Check stock availability
                if ($product->quantity_in_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->quantity_in_stock}");
                }
    
                // Calculate total price for each item
                $price = $product->selling_price;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity; // Subtotal for the item
    
                // Create order detail
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal, // Subtotal for the item
                ]);
    
                $orderDetail->load('product');
                $orderItems[] = $orderDetail;
    
                // Track stock updates
                $previousStock = $product->quantity_in_stock;
                $newStock = $previousStock - $quantity;
                $stockUpdates[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'difference' => -$quantity
                ];
    
                // Update product stock safely
                $product->decrement('quantity_in_stock', $quantity);
    
                // Add to total order amount
                $totalAmount += $subtotal;
            }
    
            // Update order with calculated total
            $order->update(['total_amount' => $totalAmount]);
    
            // Commit transaction
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_date' => $order->order_date,
                        'total_amount' => $order->total_amount
                    ],
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->customer_name,
                        'contact' => $customer->contact_name,
                        'phone' => $customer->phone
                    ],
                    'employee' => [
                        'id' => $employee->id,
                        'name' => $employee->first_name . ' ' . $employee->last_name
                    ],
                    'shipper' => [
                        'id' => $shipper->id,
                        'name' => $shipper->shipper_name,
                        'phone' => $shipper->phone
                    ],
                    'items' => $orderItems,
                    'stock_updates' => $stockUpdates,
                    'total_price' => $totalAmount
                ]
            ], 201);
    
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 422);
        }
    }
    
/* 
create raw  json:
{
    "order_date": "2025-03-31",
    "customer_id": 1,
    "employee_id": 2,
    "shipper_id": 3,
    "items": [
        {
            "product_id": 101,
            "quantity": 2
        },
        {
            "product_id": 102,
            "quantity": 1
        }
    ]
}

 */
    public function update(OrderStoreRequest $request, int $id): JsonResponse
    {
        // Validate request data (handled by OrderStoreRequest)
        $validated = $request->validated();

        // Begin transaction for order update
        DB::beginTransaction();

        try {
            // Find the order to update
            $order = Order::with('orderDetails')->findOrFail($id);
            
            // Retrieve customer, employee, and shipper details
            $customer = Customer::findOrFail($validated['customer_id']);
            $employee = Employee::findOrFail($validated['employee_id']);
            $shipper = Shipper::findOrFail($validated['shipper_id']);

            // Initialize total amount
            $totalAmount = 0;
            
            // Update order basic details
            $order->update([
                'order_date' => $validated['order_date'],
                'customer_id' => $validated['customer_id'],
                'employee_id' => $validated['employee_id'],
                'shipper_id' => $validated['shipper_id'],
            ]);

            // Delete existing order details and restore product quantities
            foreach ($order->orderDetails as $existingDetail) {
                // Restore stock
                $product = Product::findOrFail($existingDetail->product_id);
                $product->increment('quantity_in_stock', $existingDetail->quantity);
                
                // Delete the order detail
                $existingDetail->delete();
            }

            // Process new order items
            $stockUpdates = [];
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                // Check stock availability
                if ($product->quantity_in_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->quantity_in_stock}");
                }

                // Calculate total price for each item
                $price = $product->selling_price;
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;

                // Create new order detail
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $orderDetail->load('product');
                $orderItems[] = $orderDetail;

                // Track stock updates
                $previousStock = $product->quantity_in_stock;
                $newStock = $previousStock - $quantity;
                $stockUpdates[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'difference' => -$quantity
                ];

                // Update product stock safely
                $product->decrement('quantity_in_stock', $quantity);

                // Add to total order amount
                $totalAmount += $subtotal;
            }

            // Update order with calculated total
            $order->update(['total_amount' => $totalAmount]);

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order updated successfully',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_date' => $order->order_date,
                        'total_amount' => $order->total_amount
                    ],
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->customer_name,
                        'contact' => $customer->contact_name,
                        'phone' => $customer->phone
                    ],
                    'employee' => [
                        'id' => $employee->id,
                        'name' => $employee->first_name . ' ' . $employee->last_name
                    ],
                    'shipper' => [
                        'id' => $shipper->id,
                        'name' => $shipper->shipper_name,
                        'phone' => $shipper->phone
                    ],
                    'items' => $orderItems,
                    'stock_updates' => $stockUpdates,
                    'total_price' => $totalAmount
                ]
            ]);

        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 422);
        }
    }
/* 


update raw  json:

{
    "order_date": "2025-03-31",
    "customer_id": 1,
    "employee_id": 2,
    "shipper_id": 3,
    "items": [
        {
            "product_id": 101,
            "quantity": 2
        },
        {
            "product_id": 102,
            "quantity": 1
        }
    ]
}


 */

    public function show(int $id): JsonResponse
    {
        try {
            $order = Order::with(['customer', 'employee', 'shipper', 'orderDetails.product'])
                ->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'order' => $order
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete the specified order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

