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
    



    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_active')->default(1);
    });




    // Schema::create('invoices', function (Blueprint $table) {
    //         $table->id();
    //         $table->unsignedInteger('invoice_number')->unique();
    //         $table->json('product_list')->nullable();
    //         $table->decimal('total', 10, 2)->default(0);
    //         $table->json('discount_list')->nullable();
    //         $table->enum('status', ['existing', 'cancelled', 'completed', 'hold'])->default('existing');
    //         $table->date('date_of_invoice');
    //         $table->string('name_of_cashier')->nullable();
    //         $table->time('transaction_duration')->nullable();
    //         $table->decimal('payment_received', 10, 2)->nullable();
    //         $table->decimal('change_tendered', 10, 2)->nullable();
    //          $table->text('notes')->nullable();
    //         $table->timestamps();
          
    //     });
      
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('options');
            $table->timestamps();            
            // $table->unsignedBigInteger('shop_id')->nullable();
            // $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
        });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('users_profile');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
    }
};
