<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignId('admin_id')->constrained('users');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->string('product_image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Draft', 'Active', 'Inactive', 'Delete'])->default('Draft');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
