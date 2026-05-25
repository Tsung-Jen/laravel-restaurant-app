<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('opening_hours', function (Blueprint $table) {
            $table->unsignedTinyInteger('closed_except_week')->nullable()->after('is_closed');
        });
    }

    public function down(): void
    {
        Schema::table('opening_hours', function (Blueprint $table) {
            $table->dropColumn('closed_except_week');
        });
    }
};
