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
        Schema::create('pulse_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Pulse::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Tag::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['pulse_id', 'tag_id']); // prevent duplicate entries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pulse_tags');
    }
};
