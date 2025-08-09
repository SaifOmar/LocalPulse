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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ['view', 'like', 'comment'])->default('view');
            $table->foreignIdFor(\App\Models\Account::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Pulse::class)->constrained()->cascadeOnDelete();
            $table->integer('delta')->default(1);
            $table->json('meta')->nullable();
            $table->unique(['user_id', 'pulse_id', 'type']);
            $table->index(['pulse_id', 'type'], "type_pulse_id_index");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
