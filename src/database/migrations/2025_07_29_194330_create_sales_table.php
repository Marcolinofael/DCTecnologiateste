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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('costumer_id')->constrained('costumers')->onDelete('cascade');
            $table->foreignId('conditionpayment_id')->constrained('conditionspayments')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->date('sale_date');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('origin_user')->nullable();
            $table->string('last_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
