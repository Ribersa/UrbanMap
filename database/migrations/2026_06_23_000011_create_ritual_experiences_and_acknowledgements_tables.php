<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ritual_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ritual_requirement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('story');
            $table->integer('witness_count')->default(0);
            $table->timestamps();
        });

        Schema::create('ritual_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ritual_requirement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['ritual_requirement_id', 'user_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('ritual_acknowledgements');
        Schema::dropIfExists('ritual_experiences');

        Schema::enableForeignKeyConstraints();
    }
};
