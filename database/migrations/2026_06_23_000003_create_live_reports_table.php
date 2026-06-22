<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_08_145651_create_live_reports_table.php
        Schema::create('live_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mystery_id')->constrained()->onDelete('cascade');
            $table->string('status_note');
            $table->timestamps();
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_08_145651_create_live_reports_table.php
        Schema::dropIfExists('live_reports');}
        Schema::enableForeignKeyConstraints();
    }
};

