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
        Schema::table('sales', function (Blueprint $table) {
            // Remove a foreign key de product_id que não deveria estar aqui
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            
            // Remove a foreign key de conditionpayment_id pois agora será string
            $table->dropForeign(['conditionpayment_id']);
            $table->dropColumn('conditionpayment_id');
            
            // Adiciona o campo de forma de pagamento como string
            $table->string('payment_method')->after('total_amount');
            
            // Torna o cliente opcional
            $table->foreignId('costumer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('conditionpayment_id')->constrained('conditionspayments')->onDelete('cascade');
            $table->dropColumn('payment_method');
            $table->foreignId('costumer_id')->nullable(false)->change();
        });
    }
};

