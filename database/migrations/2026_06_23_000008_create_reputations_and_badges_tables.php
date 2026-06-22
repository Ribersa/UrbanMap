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
        // From 2026_06_22_020517_create_reputations_table.php
        Schema::create('reputations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // From 2026_06_22_020520_create_badges_table.php
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_22_020520_create_badges_table.php
        Schema::dropIfExists('badges');
        // From 2026_06_22_020517_create_reputations_table.php
        Schema::dropIfExists('reputations');}
        Schema::enableForeignKeyConstraints();
    }
};

