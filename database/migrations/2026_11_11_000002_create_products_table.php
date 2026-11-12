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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('variant')->default('Goreng');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->integer('weight')->default(80);
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('nutrition_information')->nullable();
            $table->text('allergen_information')->nullable();
            $table->text('preparation_instructions')->nullable();
            $table->text('storage_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

