<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Lower number = higher priority. Indexed for fast ORDER BY.
            $table->unsignedSmallInteger('priority')->index();

            // Nullable FK — a task may belong to no project.
            // cascadeOnDelete() removes tasks automatically when their project is deleted.
            $table->foreignId('project_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
