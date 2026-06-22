<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ritual_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mystery_id')->constrained()->cascadeOnDelete();
            $table->text('instruction');
            $table->enum('ritual_type', ['pantangan', 'prasyarat', 'tips'])->default('pantangan');
            $table->integer('risk_level')->default(1);
            $table->timestamps();
        });

        Schema::create('ritual_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ritual_requirement_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('ritual_items');
        Schema::dropIfExists('ritual_requirements');

        Schema::enableForeignKeyConstraints();
    }
};
