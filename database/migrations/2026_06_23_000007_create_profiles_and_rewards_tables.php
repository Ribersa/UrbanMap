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
        // From 2026_06_22_020508_create_profiles_table.php
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // From 2026_06_22_020510_create_user_rewards_table.php
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_22_020510_create_user_rewards_table.php
        Schema::dropIfExists('user_rewards');
        // From 2026_06_22_020508_create_profiles_table.php
        Schema::dropIfExists('profiles');}
        Schema::enableForeignKeyConstraints();
    }
};

