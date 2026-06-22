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
        // From 2026_06_22_020514_create_media_proofs_table.php
        Schema::create('media_proofs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_22_020514_create_media_proofs_table.php
        Schema::dropIfExists('media_proofs');}
        Schema::enableForeignKeyConstraints();
    }
};

