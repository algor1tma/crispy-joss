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
        // Mengubah tipe enum dengan menambahkan nilai baru
        DB::statement("ALTER TABLE raw_material_logs MODIFY COLUMN type ENUM('in', 'out', 'adjustment', 'production', 'expired', 'damaged') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan tipe enum ke nilai awal
        DB::statement("ALTER TABLE raw_material_logs MODIFY COLUMN type ENUM('in', 'out') NOT NULL");
    }
}; 