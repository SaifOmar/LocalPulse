<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('handle')->unique();
            $table->text('bio')->nullable();
            $table->boolean('first')->default(false);
            $table->string('password');
            $table->enum('gender', ['male', 'female', "not specified"])->default("not specified");
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->integer("num_followers")->default(0);
            $table->integer("num_following")->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
