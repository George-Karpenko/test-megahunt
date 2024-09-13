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
            $table->dropColumn(['id']);
            $table->uuid('id')->primary()->first();
            $table->string('last_name', length: 40)->after('id');
            $table->string('name', length: 40)->change();
            $table->string('middle_name', length: 40)->after('name');
            $table->string('email', length: 80)->change();
            $table->string('phone', length: 20)->after('email');
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id', 'last_name', 'middle_name', 'phone']);
            $table->dropSoftDeletes();
            $table->id()->first();
            $table->string('name')->change();
            $table->string('email')->change();
        });
    }
};
