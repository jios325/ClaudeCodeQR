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

        Schema::create('static_q_r_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('filename');
            $table->enum('content_type', ["text","email","phone","sms","whatsapp","skype","location","vcard","event","bookmark","wifi","paypal","bitcoin","2fa"]);
            $table->longText('content');
            $table->string('foreground_color')->default('#000000');
            $table->string('background_color')->default('#FFFFFF');
            $table->enum('precision', ["L","M","Q","H"])->default('L');
            $table->integer('size')->default(200);
            $table->enum('format', ["png","svg","eps"])->default('png');
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
        Schema::dropIfExists('static_q_r_codes');
    }
};
