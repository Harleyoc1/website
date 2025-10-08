<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_order');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(
            'CREATE TRIGGER set_order ' .
            'AFTER INSERT ON projects ' .
            'FOR EACH ROW ' .
            'BEGIN ' .
            'UPDATE projects SET order_index = COALESCE((SELECT MAX(order_index) FROM projects), 0) + 1 WHERE id = NEW.id;' .
            'END;');
    }
};
