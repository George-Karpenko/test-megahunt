<?php

use App\Enums\History\ActionEnum;
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
        Schema::create('histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id');
            $table->string('model_name', length: 250);
            $table->index(["model_name", "model_id"]);
            $table->json('before');
            $table->json('after');
            $table->enum('action', array_column(ActionEnum::cases(), 'value'));
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
