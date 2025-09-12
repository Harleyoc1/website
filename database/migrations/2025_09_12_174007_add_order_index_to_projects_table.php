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
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('order_index')->after('standout')->default(0);
        });
        // Statement to set order_index to max(order) + 1, or 1 if no elements
        DB::unprepared(
            'CREATE TRIGGER set_order ' .
            'AFTER INSERT ON projects ' .
            'FOR EACH ROW ' .
            'BEGIN ' .
            'UPDATE projects SET order_index = COALESCE((SELECT MAX(order_index) FROM projects), 0) + 1 WHERE id = NEW.id;' .
            'END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER set_order');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('order_index');
        });
    }
};
