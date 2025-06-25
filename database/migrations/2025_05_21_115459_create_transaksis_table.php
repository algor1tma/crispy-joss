<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->datetime('tanggal_transaksi');
                $table->integer('total_harga');           
                $table->timestamps();
                $table->softDeletes(); 
            });
        } else {
            // Check if columns exist and add them if they don't
            if (!Schema::hasColumn('transaksi', 'total_harga')) {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->integer('total_harga')->after('tanggal_transaksi');
                });
            }
            
            if (!Schema::hasColumn('transaksi', 'deleted_at')) {
                Schema::table('transaksi', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
