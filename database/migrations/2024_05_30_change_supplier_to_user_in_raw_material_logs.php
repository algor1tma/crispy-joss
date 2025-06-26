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
        Schema::table('raw_material_logs', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            // Rename the column and add new foreign key
            $table->renameColumn('supplier_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_logs', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['user_id']);
            // Rename back to original
            $table->renameColumn('user_id', 'supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }
};
