<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name_kk');
            $table->string('name_zh')->nullable();
            $table->string('iin', 12)->nullable()->unique();
            $table->string('phone');
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable();
            $table->string('category')->default('regular');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name_kk');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
