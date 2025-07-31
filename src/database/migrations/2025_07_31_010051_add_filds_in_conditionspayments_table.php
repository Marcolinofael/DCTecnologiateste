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
        Schema::table('conditionspayments', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_id')->after('id');
            $table->decimal('value', 10, 2)->after('sale_id');
            $table->date('due_date')->after('value');

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conditionspayments', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropColumn(['sale_id', 'value', 'due_date']);
        });
    }
};
