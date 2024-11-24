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
        Schema::create('shared_task_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shared_with')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_list_id')->constrained('task_lists')->onDelete('cascade');
            $table->enum("permission", ["edit", "view"])->default("view");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_tasks');
    }
};
