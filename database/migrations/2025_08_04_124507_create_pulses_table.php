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
        Schema::create('pulses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Account::class)->constrained()->cascadeOnDelete();
            $table->text('caption')->nullable();
            $table->enum('type', ['image','video'])->default("image");
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pulses');
    }
};
