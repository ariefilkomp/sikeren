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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_opd',9)->after('atasan_id')->nullable();
            $table->string('nip')->after('atasan_id')->nullable();
            $table->string('nip_atasan')->after('nip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode_opd');
            $table->dropColumn('nip');
            $table->dropColumn('nip_atasan');
        });
    }
};
