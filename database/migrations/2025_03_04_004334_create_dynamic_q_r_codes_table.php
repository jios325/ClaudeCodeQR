<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('dynamic_q_r_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('filename');
            $table->string('redirect_identifier')->unique();
            $table->string('url');
            $table->string('foreground_color')->default('#000000');
            $table->string('background_color')->default('#FFFFFF');
            $table->enum('precision', ["L","M","Q","H"])->default('L');
            $table->integer('size')->default(200);
            $table->integer('scan_count')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_q_r_codes');
    }
};
