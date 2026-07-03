<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('id')
                ->constrained('departments')->nullOnDelete();
            $table->string('name_kk')->nullable()->after('name');
            $table->string('name_zh')->nullable()->after('name_kk');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn(['name_kk', 'name_zh']);
        });
    }
};
