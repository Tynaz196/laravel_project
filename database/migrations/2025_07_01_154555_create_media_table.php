<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_column')->nullable();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->string('manipulations')->nullable();
            $table->string('custom_properties')->nullable();
            $table->string('generated_conversions')->nullable();
            $table->string('responsive_images')->nullable();
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->string('collection_name');
            $table->string('uuid')->nullable();
            $table->json('custom_properties_json')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
