<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // php artisan make:migration add_restored_at_to_seguimientos_table --table=seguimientos
public function up(): void
{
    Schema::table('seguimientos', function (Blueprint $table) {
        $table->timestamp('restored_at')->nullable()->after('deleted_at');
    });
}
public function down(): void
{
    Schema::table('seguimientos', function (Blueprint $table) {
        $table->dropColumn('restored_at');
    });
}
};
