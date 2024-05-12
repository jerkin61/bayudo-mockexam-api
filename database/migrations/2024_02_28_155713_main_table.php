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
            Schema::create('examlist', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('passing_rate');
            $table->integer('number_of_items')->nullable();
            $table->decimal('total_time_limit_hours', 5, 2)->nullable(); // Stores up to 999.99 hours
            $table->integer('number_of_questions')->nullable();
            $table->string('category')->nullable();
            $table->string('exam_per_coverage')->nullable();
            $table->text('instruction')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('examcategory', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->string('slug')->nullable();
            $table->integer('items_count')->nullable();
            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')->references('id')->on('examlist')->onDelete('cascade');
            $table->integer('time_limit')->nullable();
            $table->integer('time_limit_per_item')->nullable();
            $table->text('description')->nullable();
            $table->text('instruction')->nullable();
            $table->integer('break_time')->nullable();
            $table->timestamps();
        });
            Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('question_no');
            $table->text('question');
            $table->string('answer'); // Changed to string
            $table->json('choices'); // Store choices as JSON array
            $table->unsignedBigInteger('exam_category_id');
            $table->foreign('exam_category_id')->references('id')->on('examcategory')->onDelete('cascade');
            $table->decimal('time_left', 8, 2)->nullable(); // Changed to decimal
            $table->float('time')->nullable(); // Changed to float
            $table->boolean('answered')->default(false); // Changed to bool
            $table->string('right_ans')->nullable(); // Changed to string
            $table->text('explanation');
            $table->timestamps();
           $table->integer('set')->nullable();
            $table->boolean('reviewed')->default(false);           
        });

      Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_code', 8)->unique(); // 8-character unique code
            $table->string('school')->nullable();
            $table->integer('limitCount')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Reference to group leader
            $table->timestamps();

            // Define foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            // Ensure uniqueness of pairs (user_id, group_id)
            $table->unique(['user_id', 'group_id']);
        });

        Schema::create('exam_list_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_list_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('exam_list_id')->references('id')->on('examlist')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            // Ensure uniqueness of pairs (user_id, group_id)
            $table->unique(['exam_list_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('examlist');
         Schema::dropIfExists('examcategory');
         Schema::dropIfExists('questions');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('exam_list_group');
    }
};
