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
        Schema::create('orders', function (Blueprint $table) {
$table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('order_number')->unique();
    $table->decimal('total_amount', 15, 0); 
    $table->decimal('discount_amount', 15, 0)->default(0);
    $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'cancelled'])->default('pending');
    $table->text('address_snapshot'); 
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
