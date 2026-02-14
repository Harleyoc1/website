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
        Schema::table('registration_tokens', function (Blueprint $table) {
            $table->renameColumn('is_admin', 'permission_level');
            $table->integer('permission_level')->unsigned()->default(0)->change();
        });
        DB::table('registration_tokens')
            ->where('permission_level', '=', 1)
            ->update(['permission_level' => 2]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_tokens', function (Blueprint $table) {
            $table->renameColumn('permission_level', 'is_admin');
            $table->boolean('is_admin')->default(false)->change();
        });
        DB::table('registration_tokens')
            ->where('is_admin', '>', 1)
            ->update(['is_admin' => 1]);
    }
};
