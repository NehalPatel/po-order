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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('po_number');
            $table->date('po_date');
            $table->foreignId('vendor_id')->constrained()->onDelete('restrict');
            $table->decimal('sub_total', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('shipping', 15, 2);
            $table->decimal('other', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('draft'); // e.g., draft, sent, approved, completed
            $table->date('expected_delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
