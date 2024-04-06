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
        Schema::create('question_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id'); // Link to the question being commented on
            $table->text('suggested_question')->nullable(); // User's suggestion for the question text
            $table->string('suggested_answer')->nullable(); // User's suggestion for the answer
            $table->json('suggested_choices')->nullable(); // User's suggested choices, stored as a JSON array
            $table->string('suggested_right_ans')->nullable(); // User's suggestion for the correct answer
            $table->text('suggested_explanation')->nullable(); // User's suggestion for the explanation
            $table->text('user_feedback')->nullable(); // Any additional feedback from the user
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
        Schema::dropIfExists('question_feedback');
    }
};
