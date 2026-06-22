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
        // From 2026_06_22_020513_create_mystery_bookmarks_table.php
        Schema::create('mystery_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // From 2026_06_22_020516_create_mystery_comments_table.php
        Schema::create('mystery_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // From 2026_06_22_020519_create_scary_ratings_table.php
        Schema::create('scary_ratings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_22_020519_create_scary_ratings_table.php
        Schema::dropIfExists('scary_ratings');
        // From 2026_06_22_020516_create_mystery_comments_table.php
        Schema::dropIfExists('mystery_comments');
        // From 2026_06_22_020513_create_mystery_bookmarks_table.php
        Schema::dropIfExists('mystery_bookmarks');}
        Schema::enableForeignKeyConstraints();
    }
};

