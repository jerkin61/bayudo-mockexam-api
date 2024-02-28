<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        $table->json('avatar')->nullable();
        $table->text('bio')->nullable();
        $table->json('socials')->nullable();
        $table->string('contact')->nullable();
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users');
        $table->timestamps();
    });

    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug');
        $table->boolean('is_active')->default(1);
        $table->unsignedBigInteger('parent')->nullable();
        $table->foreign('parent')->references('id')->on('categories')->onDelete('cascade');          
        $table->text('response')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });    
    
    Schema::create('company', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('owner_id');
        $table->foreign('owner_id')->references('id')->on('users');
        $table->string('company_name')->nullable();
        $table->string('slug')->nullable();
        $table->text('description')->nullable();
        $table->text('business_type')->nullable();
        $table->json('cover_image')->nullable();
        $table->json('logo')->nullable();
        $table->boolean('is_active')->default(false);
        $table->json('address')->nullable();
        $table->json('settings')->nullable();
        $table->timestamps();
    });



    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_active')->default(1);
    });

        Schema::create('shop', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('shop_id');
        $table->foreign('shop_id')->references('id')->on('company');
        $table->string('shop_name')->nullable();
        $table->string('slug')->nullable();
        $table->text('description')->nullable();
        $table->text('shop_type')->nullable();
        $table->json('cover_image')->nullable();
        $table->json('logo')->nullable();
        $table->boolean('is_active')->default(true);
        $table->json('settings')->nullable();
        $table->timestamps();
    });
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug');
        $table->text('description')->nullable();
        $table->float('price')->nullable()->default(0);
        $table->unsignedBigInteger('shop_id')->nullable();
        $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
        $table->float('sale_price')->nullable();
        $table->string('sku')->nullable();
        $table->float('quantity')->default(0);
        $table->boolean('in_stock')->default(true);
        $table->boolean('is_taxable')->default(false);
        $table->enum('status', ['publish', 'draft'])->default('publish');
        $table->string('height')->nullable();
        $table->string('width')->nullable();
        $table->string('length')->nullable();
        $table->json('image')->nullable();
        $table->json('gallery')->nullable();
        $table->string('tax')->nullable();
        $table->string('unit')->nullable();
        $table->float('wholesale_price')->nullable();
        $table->string('stack_label')->nullable();
        $table->integer('stack_size')->nullable();        
        $table->softDeletes();
        $table->timestamps();
    });


       Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('slug');
            $table->unsignedBigInteger('parent')->nullable();
            $table->foreign('parent')->references('id')->on('categories')->onDelete('cascade');
            $table->enum('stat', ['unverified', 'verified', 'more-info', 'scam'])->nullable();
            $table->text('response')->nullable();
            $table->string('date_verified')->nullable();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->string('platform');
            $table->enum('type', ['business', 'person', 'website'])->nullable();
            $table->timestamps();
      });
    Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_number')->unique();
            $table->json('product_list')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->json('discount_list')->nullable();
            $table->enum('status', ['existing', 'cancelled', 'completed', 'hold'])->default('existing');
            $table->date('date_of_invoice');
            $table->string('name_of_cashier')->nullable();
            $table->time('transaction_duration')->nullable();
            $table->decimal('payment_received', 10, 2)->nullable();
            $table->decimal('change_tendered', 10, 2)->nullable();
             $table->text('notes')->nullable();
            $table->timestamps();
          
        });

            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->string('type_of_unit')->nullable();
                $table->string('industry')->nullable();
                $table->string('basis_of_pay')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('shop_id')->nullable();
                $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
                $table->float('service_fee')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->unsignedInteger('duration_minutes')->nullable();
                $table->json('attachment')->nullable();
                $table->json('features')->nullable();
                $table->json('image')->nullable();
                $table->json('gallery')->nullable();
                $table->string('location')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });       
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('options');
            $table->timestamps();            
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
        });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('products');
        Schema::dropIfExists('users_profile');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('shop');
        Schema::dropIfExists('company');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('services');
        Schema::dropIfExists('settings');
    }
};
