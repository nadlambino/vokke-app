<?php

use App\Enums\Gender;
use App\Enums\PetType;
use App\Models\User;
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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('type')->default(PetType::Kangaroo);
            $table->string('name');
            $table->string('nickname')->nullable()->default(null);
            $table->decimal('weight');
            $table->decimal('height');
            $table->enum('gender', [Gender::Male->value, Gender::Female->value]);
            $table->string('color')->nullable()->default(null);
            $table->string('friendliness')->nullable()->default(null);
            $table->date('birthday')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
