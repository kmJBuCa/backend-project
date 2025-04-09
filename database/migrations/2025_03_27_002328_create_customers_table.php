<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('status')->default('active'); // active, inactive, suspended
            $table->string('customer_type')->default('regular'); // regular, premium, vip
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
        });

        /* 
        
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
        | Relationships                |
        +------------------------------+
        | Customer has many Orders (hasMany)
        +------------------------------+
        */
    }
  
 
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};