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
        Schema::create('vidoes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Account::class)->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('path');
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vidoes');
    }
};
