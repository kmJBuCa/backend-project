
This e-commerce system is designed to manage inventory, customer orders, suppliers, and shipping operations. It provides a comprehensive solution for online retail businesses with a modern Category interface and robust backend functionality.


## Getting Started

### Prerequisites

- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [LAMPP](https://www.apachefriends.org/index.html)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/kmJBuCa/backend-project.git
   cd backend-project
   ```

2. Install dependencies:
   ```bash
   composer install
   cp .env.example .env
   ```

3. Set up the environment:
   ```bash
   php artisan key:generate
   php artisan migrate
   sudo /opt/lampp/lampp restart
   php artisan serve
   ```

## API Reference

### Endpoints

<details>
<summary><strong>Categories</strong></summary>

- **GET** `/api/v1/categories` - List all categories
- **POST** `/api/v1/categories` - Create a new category
- **GET** `/api/v1/categories/{id}` - Get a single category
- **PUT** `/api/v1/categories/{id}` - Update a category
- **DELETE** `/api/v1/categories/{id}` - Delete a category
</details>

<details>
<summary><strong>Products</strong></summary>

- **GET** `/api/v1/products` - List all products
- **POST** `/api/v1/products` - Create a new product
- **GET** `/api/v1/products/{id}` - Get a single product
- **PUT** `/api/v1/products/{id}` - Update a product
- **DELETE** `/api/v1/products/{id}` - Delete a product
</details>

<details>
<summary><strong>Customers</strong></summary>

- **GET** `/api/v1/customers` - List all customers
- **POST** `/api/v1/customers` - Create a new customer
- **GET** `/api/v1/customers/{id}` - Get a single customer
- **PUT** `/api/v1/customers/{id}` - Update a customer
- **DELETE** `/api/v1/customers/{id}` - Delete a customer
</details>

<details>
<summary><strong>Employees</strong></summary>

- **GET** `/api/v1/employees` - List all employees
- **POST** `/api/v1/employees` - Create a new employee
- **GET** `/api/v1/employees/{id}` - Get a single employee
- **PUT** `/api/v1/employees/{id}` - Update an employee
- **DELETE** `/api/v1/employees/{id}` - Delete an employee
</details>

<details>
<summary><strong>Shippers</strong></summary>

- **GET** `/api/v1/shippers` - List all shippers
- **POST** `/api/v1/shippers` - Create a new shipper
- **GET** `/api/v1/shippers/{id}` - Get a single shipper
- **PUT** `/api/v1/shippers/{id}` - Update a shipper
- **DELETE** `/api/v1/shippers/{id}` - Delete a shipper
</details>

<details>
<summary><strong>Orders & Order Details</strong></summary>

- **GET** `/api/v1/orders` - List all orders
- **POST** `/api/v1/orders` - Create a new order
- **GET** `/api/v1/orders/{id}` - Get a single order
- **PUT** `/api/v1/orders/{id}` - Update an order
- **DELETE** `/api/v1/orders/{id}` - Delete an order

- **GET** `/api/v1/order-details` - List all order details
- **POST** `/api/v1/order-details` - Create a new order detail
- **GET** `/api/v1/order-details/{id}` - Get a single order detail
- **PUT** `/api/v1/order-details/{id}` - Update an order detail
- **DELETE** `/api/v1/order-details/{id}` - Delete an order detail
</details>




## Database Schema

<details>
<summary><strong>Categories</strong></summary>

```
+------------------------------+
| categories                   |
+------------------------------+
| id                           | bigIncrements
| category_name                | string(100) unique
| description                  | text
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
| relationships:               |
| - category hasMany product   |
+------------------------------+
```
### php artisan tinker
```
use App\Models\Category; 
for ($i = 1; $i <= 9; $i++) {
    Category::create([
        'category_name' => 'Category ' . $i,
        'description' => 'Description for Category ' . $i
    ]);
}
```

</details>

<details>
<summary><strong>Suppliers</strong></summary>

```
+------------------------------+
| suppliers                    |
+------------------------------+
| id                           | bigIncrements
| supplier_name                | string(100)
| contact_person               | string(100)
| phone                        | string(20)
| email                        | string(100)
| website                      | string(255)
| bio                          | text
| address                      | string(255)
| city                         | string(50)
| country                      | string(50)
| brand_name                   | string(100)
| active                       | boolean
| bank_name                    | string
| bank_account_number          | string
| bank_account_name            | string
| logo                         | string
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
| relationships:               |
| - supplier hasMany Product   |
+------------------------------+
```
### php artisan tinker
```
use App\Models\Supplier;
for ($i = 1; $i <= 9; $i++) {
    Supplier::create([
        'supplier_name' => 'Supplier ' . $i,
        'contact_person' => 'Contact ' . $i,
        'phone' => '555-020' . $i,
        'email' => 'supplier' . $i . '@example.com',
        'website' => 'https://supplier' . $i . '.com',
        'bio' => 'Bio for Supplier ' . $i,
        'address' => 'Address ' . $i,
        'city' => 'City ' . $i,
        'country' => 'Country ' . $i,
        'brand_name' => 'Brand ' . $i,
        'active' => $i % 2 == 0 ? 1 : 0, 
        'bank_name' => 'Bank ' . $i,
        'bank_account_number' => 'BA' . str_pad($i, 8, '0', STR_PAD_LEFT),
        'bank_account_name' => 'Account ' . $i,
        'logo' => 'logo' . $i . '.png' 
    ]);
}
```
</details>

<details>
<summary><strong>Products</strong></summary>

```
+------------------------------+
| products                     |
+------------------------------+
| id                           | bigIncrements
| product_name                 | string
| cost_price                   | decimal(10,2)
| selling_price                | decimal(10,2)
| quantity_in_stock            | integer, default(0)
| minimum_stock_level          | integer, default(0)
| status                       | enum, default('active')
| image                        | string, nullable
| barcode                      | string, nullable
| description                  | text, nullable
| brand                        | string, nullable
| model                        | string, nullable
| color                        | string, nullable
| size                         | string, nullable
| weight                       | string, nullable
| dimensions                   | string, nullable
| warranty                     | string, nullable
| country_of_origin            | string, nullable
| supplier_id                  | unsignedBigInteger, foreign key
| category_id                  | unsignedBigInteger, foreign key
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
| relationships:               |
| - Product belongs to Category|
| - Product belongs to Supplier|
+------------------------------+
```
### php artisan tinker
```
use App\Models\Product;
for ($i = 1; $i <= 9; $i++) {
    Product::create([
        'product_name' => 'Product ' . $i,
        'cost_price' => rand(50, 200) + (rand(0, 99) / 100), 
        'selling_price' => rand(100, 300) + (rand(0, 99) / 100),
        'quantity_in_stock' => rand(10, 100), 
        'minimum_stock_level' => rand(5, 20), 
        'status' => $i % 2 == 0 ? 'active' : 'inactive', 
        'image' => 'product' . $i . '.jpg', 
        'barcode' => 'BAR' . str_pad($i, 10, '0', STR_PAD_LEFT), 
        'description' => 'Description for Product ' . $i,
        'brand' => 'Brand ' . $i,
        'model' => 'Model ' . $i,
        'color' => $i % 3 == 0 ? 'Red' : ($i % 3 == 1 ? 'Blue' : 'Green'), 
        'size' => $i % 2 == 0 ? 'Large' : 'Small', 
        'weight' => rand(1, 10) + (rand(0, 99) / 100), 
        'dimensions' => rand(10, 50) . 'x' . rand(10, 50) . 'x' . rand(10, 50), 
        'warranty' => $i % 2 == 0 ? '1 year' : '6 months', 
        'country_of_origin' => 'Country ' . $i,
        'supplier_id' => rand(1, 9), 
        'category_id' => rand(1, 9)  
    ]);
}

```
</details>

<details>
<summary><strong>Employees, Shippers, Customers</strong></summary>

```
+------------------------------+
| employees                    |
+------------------------------+
| id                           | bigIncrements
| first_name                   | string
| last_name                    | string
| position                     | string
| department                   | string
| hire_date                    | date
| phone                        | string, nullable
| email                        | string, unique
| address                      | text, nullable
| photo                        | string, nullable
| gender                       | enum('male','female','other'), nullable
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
```
### php artisan tinker
```
use App\Models\Employee;
for ($i = 1; $i <= 9; $i++) {
    Employee::create([
        'first_name' => 'Employee' . $i,
        'last_name' => 'Smith' . $i,
        'position' => 'Position ' . $i,
        'department' => 'Dept ' . $i,
        'hire_date' => now()->subDays(rand(1, 365))->toDateString(), 
        'phone' => '555-010' . $i,
        'email' => 'employee' . $i . '@company.com',
        'address' => 'Address ' . $i,
        'photo' => 'photo' . $i . '.jpg', 
        'gender' => $i % 2 == 0 ? 'Female' : 'Male' 
    ]);
}
```

```
+------------------------------+
| shippers                     |
+------------------------------+
| id                           | primary key, auto-incrementing
| shipper_name                 | string
| contact_person               | string nullable
| phone                        | string
| address                      | text nullable  
| shipping_methods             | json nullable
| email                        | string nullable
| notes                        | text nullable
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
```
### php artisan tinker
```
use App\Models\Shipper;
for ($i = 1; $i <= 9; $i++) {
    Shipper::create([
        'shipper_name' => 'Shipper ' . $i,
        'contact_person' => 'Contact ' . $i,
        'phone' => '555-030' . $i,
        'address' => 'Address ' . $i,
        'shipping_methods' => $i % 2 == 0 ? 'Ground, Air' : 'Ground', 
        'email' => 'shipper' . $i . '@shipping.com',
        'notes' => 'Notes for Shipper ' . $i
    ]);
}
```

```
+------------------------------+
| customers                    |
+------------------------------+
| id                           | primary key
| customer_name                | string
| contact_name                 | string
| phone                        | string nullable
| email                        | string unique
| address                      | string nullable
| city                         | string nullable
| state                        | string nullable
| zip                          | string nullable
| country                      | string nullable
| company                      | string nullable
| website                      | string nullable
| status                       | string default 'active'
| customer_type                | string default 'regular'
| bank_name                    | string nullable
| account_name                 | string nullable
| account_number               | string nullable
| notes                        | text nullable
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
```
### php artisan tinker
```
use App\Models\Customer;
for ($i = 1; $i <= 9; $i++) {
    Customer::create([
        'customer_name' => 'Customer ' . $i,
        'contact_name' => 'Contact ' . $i,
        'phone' => '555-000' . $i,
        'email' => 'customer' . $i . '@example.com',
        'address' => 'Address ' . $i,
        'city' => 'City ' . $i,
        'state' => 'ST',
        'zip' => '0000' . $i,
        'country' => 'Country ' . $i,
        'company' => 'Company ' . $i,
        'website' => 'https://company' . $i . '.com',
        'status' => $i % 3 == 0 ? 'inactive' : 'active', 
        'customer_type' => $i % 3 == 1 ? 'regular' : ($i % 3 == 2 ? 'premium' : 'vip'),
        'bank_name' => 'Bank ' . $i,
        'account_name' => 'Account ' . $i,
        'account_number' => 'ACC' . str_pad($i, 6, '0', STR_PAD_LEFT),
        'notes' => 'Notes for Customer ' . $i
    ]);
}
```
</details>

<details>
<summary><strong>Orders & Order Details</strong></summary>

```
+------------------------------+
| Order                        |
+------------------------------+
| id                           | primary key
| order_date                   | date
| total_amount                 | decimal(10, 2)
| status                       | enum('pending', 'processing', 'shipped', 'delivered', 'cancelled')
|                              | default('pending')
+------------------------------+
| customer_id                  | unsignedBigInteger to customers table
| employee_id                  | unsignedBigInteger to employees table
| shipper_id                   | unsignedBigInteger to shippers table
+------------------------------+
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
```
### php artisan tinker
```
use App\Models\Order;
for ($i = 1; $i <= 9; $i++) {
    Order::create([
        'order_date' => now()->subDays(rand(1, 30))->toDateString(), 
        'total_amount' => rand(100, 1000) + (rand(0, 99) / 100), 
        'customer_id' => rand(1, 9), 
        'employee_id' => rand(1, 9), 
        'shipper_id' => rand(1, 3)   
    ]);
}
```

```
+------------------------------+
| OrderDetail                  |
+------------------------------+
| id                           | primary key
| quantity                     | integer
| price                        | decimal(10, 2)
| order_id                     | unsignedBigInteger to orders table
| product_id                   | unsignedBigInteger to products table
+------------------------------+
| created_at                   | timestamp
| updated_at                   | timestamp
+------------------------------+
```
### php artisan tinker
```
use App\Models\OrderDetail;
for ($i = 1; $i <= 9; $i++) {
    $quantity = rand(1, 10); 
    $price = rand(10, 100) + (rand(0, 99) / 100); 
    OrderDetail::create([
        'quantity' => $quantity,
        'price' => $price,
        'subtotal' => $quantity * $price, 
        'order_id' => rand(1, 9), 
        'product_id' => rand(1, 5) 
    ]);
}
```
</details>


</details> 

## SQL Queries

1. Select customer name together with each order the customer made
2. Select order ID together with the name of the employee who handled the order
3. Select customers who have not placed any orders yet
4. Select order ID together with the names of products
5. Select products that no one bought
6. Select customer together with the products that they bought
7. Select product names together with the name of the corresponding category
8. Select orders together with the name of the shipping company
9. Select customers with ID greater than 50 together with each order they made
10. Select employees together with orders with order ID greater than 10400
11. Select the most expensive product
12. Select the second most expensive product
13. Select name and price of each product, sort the result by price in decreasing order
14. Select 5 most expensive products
15. Select 5 most expensive products without the most expensive (in final 4 products)
16. Select the name of the cheapest product (only name) without using LIMIT and OFFSET
17. Select employees with last name that starts with 'D'
18. Select number of employees with last name that starts with 'D'
19. Select customers with last name that starts with 'D'
20. Select customer name together with the number of orders made by the corresponding customer, sort the result by number of orders in descending order
21. Add up the price of all products
22. Select order ID together with the total price of that order, order the result by total price of the order in increasing order
23. Select customer who spent the most money
24. Select customer who spent the most money and lives in Canada
25. Select customer who spent the second most money
26. Select shipper together with the total price of processed orders

## Additional Queries

- Show the total cost for each order
- Show the customer name for the orders
- Show the total amount spent by each customer
- Show how many customers per country
- Show the total sales by country
- Show the total sales by employee
- Show how many products were sold for each product
- Show the total sales by product
- Show how many products were sold for each category
- Show the total sales by category



* Histories
```
php artisan make:model Category -m 
php artisan make:model Supplier -m 
php artisan make:model Product -m 
php artisan make:model Employee -m
php artisan make:model Shipper -m
php artisan make:model Customer -m
php artisan make:model OrderDetail -m
php artisan make:model Order -m 

php artisan install:api
php artisan make:controller API/v2025/ProductController --api
php artisan make:controller API/v2025/SupplierController --api
php artisan make:controller API/v2025/CategoryController --api
php artisan make:controller API/v2025/OrderDetailController --api
php artisan make:controller API/v2025/OrderController --api
php artisan make:controller API/v2025/CustomerController --api
php artisan make:controller API/v2025/EmployeeController --api
php artisan make:controller API/v2025/ShipperController --api


composer install
php artisan key:generate
php artisan migrate
php artisan serve
composer dump-autoload
php artisan route:clear
php artisan route:cache


App\Models\Category::all()
App\Models\Category::find(1)
DB::table('categories')->where('id', 1)->first()
App\Models\Category::with('products')->find(1)
App\Models\Category::destroy(1)



git init
git remote add origin https://github.com/kmJBuCa/backend-project.git
git add .
git commit -m "Add my backend project"
git push -u origin main

```
