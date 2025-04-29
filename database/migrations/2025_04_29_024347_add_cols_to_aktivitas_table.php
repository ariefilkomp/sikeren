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
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->boolean('notif_on_publish')->after('published')->default(false);
            $table->integer('min_jam')->after('published')->default(2); // Misalnya, setiap 2 minggu, setiap 3 bulan, dll.
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->after('published')->default('none');
            $table->integer('recurrence_interval')->after('published')->nullable(); // Misalnya, setiap 2 minggu, setiap 3 bulan, dll.
            $table->date('recurrence_ends_at')->after('published')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->dropColumn('notif_on_publish');
            $table->dropColumn('min_jam');
            $table->dropColumn('recurrence_type');
            $table->dropColumn('recurrence_interval');
            $table->dropColumn('recurrence_ends_at');
        });
    }
};
