<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mysteries', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('is_verified');
        });

        Schema::table('live_reports', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('status_note');
        });
    }

    public function down(): void
    {
        Schema::table('mysteries', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('live_reports', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
