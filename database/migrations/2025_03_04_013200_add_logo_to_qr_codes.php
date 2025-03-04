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
        Schema::table('dynamic_q_r_codes', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('background_color');
            $table->boolean('has_logo')->default(false)->after('logo_path');
            $table->integer('logo_size')->default(50)->after('has_logo'); // Logo size as percentage of QR code
            $table->string('gradient_start_color')->nullable()->after('logo_size');
            $table->string('gradient_end_color')->nullable()->after('gradient_start_color');
            $table->boolean('use_gradient')->default(false)->after('gradient_end_color');
            $table->string('eye_color')->nullable()->after('use_gradient');
            $table->string('style')->default('square')->after('eye_color'); // square, round, dot, etc.
        });
        
        Schema::table('static_q_r_codes', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('background_color');
            $table->boolean('has_logo')->default(false)->after('logo_path');
            $table->integer('logo_size')->default(50)->after('has_logo'); // Logo size as percentage of QR code
            $table->string('gradient_start_color')->nullable()->after('logo_size');
            $table->string('gradient_end_color')->nullable()->after('gradient_start_color');
            $table->boolean('use_gradient')->default(false)->after('gradient_end_color');
            $table->string('eye_color')->nullable()->after('use_gradient');
            $table->string('style')->default('square')->after('eye_color'); // square, round, dot, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_q_r_codes', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'has_logo',
                'logo_size',
                'gradient_start_color',
                'gradient_end_color',
                'use_gradient',
                'eye_color',
                'style',
            ]);
        });
        
        Schema::table('static_q_r_codes', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'has_logo',
                'logo_size',
                'gradient_start_color',
                'gradient_end_color',
                'use_gradient',
                'eye_color',
                'style',
            ]);
        });
    }
};
