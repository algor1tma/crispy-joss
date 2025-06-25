<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create users table first
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('roles', ['admin', 'karyawan', 'pelanggan'])->default('pelanggan');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create produks table
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->decimal('harga_produk', 10, 2);
            $table->integer('stok_produk')->default(0);
            $table->string('foto')->nullable();
            $table->text('deskripsi_produk')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create raw_materials table
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('stock', 10, 2)->default(0);
            $table->string('unit');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create suppliers table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create raw_material_logs table
        Schema::create('raw_material_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->enum('type', ['in', 'out']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create product_recipes table
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Create transaksi table
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->string('metode_pembayaran')->default('cash');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create transaksi_details table
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_details');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('product_recipes');
        Schema::dropIfExists('raw_material_logs');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('produks');
        // Don't drop users table as it might be needed by other parts
    }
}; 