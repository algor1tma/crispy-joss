<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the existing table
        Schema::dropIfExists('product_recipes');
        
        // Create the table with proper structure
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_recipes');
    }
}; 