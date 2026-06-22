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
        // From 2026_06_08_145607_create_mysteries_table.php
        Schema::create('mysteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('category', ['penampakan', 'tempat_bersejarah', 'mitos_hewan', 'kutukan']);
            $table->integer('scary_level')->default(1);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            // Indexing for performance
            $table->index(['latitude', 'longitude']);
        });
        // From 2026_06_15_043140_add_image_path_to_mysteries_and_live_reports.php
        Schema::table('mysteries', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('is_verified');
        });

        Schema::table('live_reports', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('status_note');
        });}
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
{
        // From 2026_06_15_043140_add_image_path_to_mysteries_and_live_reports.php
        Schema::table('mysteries', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('live_reports', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
        // From 2026_06_08_145607_create_mysteries_table.php
        Schema::dropIfExists('mysteries');}
        Schema::enableForeignKeyConstraints();
    }
};

