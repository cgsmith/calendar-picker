<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_questions', function (Blueprint $table) {
            $table->integer('parent_service_question_id')->after('service_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_questions', function (Blueprint $table) {
            $table->dropColumn('parent_service_question_id');
        });
    }
};
