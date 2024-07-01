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
        Schema::create('exam_taken', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference to user
            $table->unsignedBigInteger('exam_id'); // Reference to ExamList
            $table->integer('take')->default(0); // How many times the exam has been taken
            $table->timestamp('time_done')->nullable(); // Time when the exam was completed
            $table->timestamp('time_started')->nullable(); // Time when the exam was completed
            $table->integer('number_of_items'); // Number of items in the exam
            $table->boolean('pass')->nullable(); // Whether the exam was passed
            $table->float('exam_result')->nullable(); // The result of the exam as a float
            $table->float('exam_percentage', 5, 2)->nullable(); // Exam percentage with 2 decimal places
            $table->timestamps();
            $table->boolean('completed')->default(false);
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('examlist')->onDelete('cascade');
        });

         Schema::create('exam_category_taken', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_taken_id'); // Reference to the ExamTake model
            $table->boolean('answered')->nullable();;
            $table->timestamp('time_done')->nullable();;
            $table->integer('number_of_items')->nullable();;
            $table->boolean('pass')->nullable();
            $table->float('exam_result')->nullable();; // Assuming a precision of 8 and a scale of 2
            $table->float('exam_percentage', 5, 2); // Assuming a precision of 5 and a scale of 2
            $table->unsignedBigInteger('exam_category_id'); // Reference to ExamCategory model
            $table->boolean('completed')->default(false);
            $table->timestamps();        
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_taken_id')->references('id')->on('exam_taken')->onDelete('cascade');
            $table->foreign('exam_category_id')->references('id')->on('examcategory')->onDelete('cascade');
        });

        Schema::create('answer_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_taken_category_id'); // Use unsignedBigInteger for foreign key
            $table->timestamp('time_done')->nullable();;
            $table->timestamp('time_started')->nullable();;
            $table->integer('question_no');
            $table->unsignedBigInteger('answered_id'); // Assuming question_id is also an unsigned big integer
            $table->string('user_answer')->nullable();;
            $table->string('right_answer')->nullable();;
            $table->boolean('correct')->nullable();
            $table->timestamps();

            // Define foreign keys
            $table->foreign('exam_taken_category_id')->references('id')->on('exam_category_taken')->onDelete('cascade');
            $table->foreign('answered_id')->references('id')->on('questions')->onDelete('cascade');
        });
        Schema::create('question_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id'); 
            $table->text('suggested_question')->nullable();
            $table->string('suggested_answer')->nullable(); 
            $table->json('suggested_choices')->nullable(); 
            $table->string('suggested_right_ans')->nullable(); 
            $table->text('suggested_explanation')->nullable();
            $table->text('user_feedback')->nullable(); 
            $table->unsignedBigInteger('submitted_id');
            $table->foreign('submitted_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_exams');
        Schema::dropIfExists('exam_category_taken');
        Schema::dropIfExists('exam_taken');
        Schema::dropIfExists('question_feedback');
    }
};
