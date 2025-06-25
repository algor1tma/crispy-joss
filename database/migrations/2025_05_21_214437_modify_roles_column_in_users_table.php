<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRolesColumnInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ganti kolom 'roles' menjadi lebih besar
            $table->string('roles', 50)->change();  // Bisa kamu sesuaikan panjangnya
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke ukuran semula (misalnya 5 karakter)
            $table->string('roles', 5)->change();
        });
    }
}
