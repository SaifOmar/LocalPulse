<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Pulse::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Account::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Comment::class)->nullable();
            $table->string('content');
            $table->enum('type', ['comment_comment', 'pulse_comment'])->default('pulse_comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
