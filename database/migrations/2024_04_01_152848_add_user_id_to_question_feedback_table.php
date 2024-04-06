<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{

    Schema::table('question_feedback', function (Blueprint $table) {
        $table->unsignedBigInteger('submitted_id');
        $table->foreign('submitted_id')->references('id')->on('users')->onDelete('cascade');
    });

}

public function down()
{
    Schema::table('question_feedback', function (Blueprint $table) {
        $table->dropForeign(['submitted_id']);
        $table->dropColumn('submitted_id');
    });
}
};
