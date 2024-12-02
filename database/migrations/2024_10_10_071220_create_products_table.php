<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
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
            $table->string('title',200)->nullable();
            $table->string('slug',400)->nullable();
            $table->string('measure',30)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('published')->default(0);
            $table->boolean('inStock')->default(0);
            $table->decimal('price',10,2)->nullable();
            $table->string('currency',30)->nullable();
            $table->unsignedBigInteger('vendor_code')->nullable();
            $table->string('country',30)->nullable();
            $table->string('keywords_ua',300)->nullable();
            $table->string('disk_type',30)->nullable();
            $table->string('work_materials',100)->nullable();
            $table->string('compatibility',100)->nullable();
            $table->float('mass_without_package')->nullable();
            $table->float('mass_in_package')->nullable();
            $table->string('height_without_package', 30)->nullable();
            $table->string('height_in_package', 30)->nullable();
            $table->string('type_of_segments',30)->nullable();
            $table->string('diameter_of_disk', 30)->nullable();
            $table->string('diameter_of_fit', 30)->nullable();
            $table->string('diamond_layer_height', 30)->nullable();
            $table->string('diamond_layer_width', 30)->nullable();
            $table->string('drill_type',30)->nullable();
            $table->string('drill_diameter', 30)->nullable();
            $table->string('end_type', 30)->nullable();
            $table->string('length',30)->nullable();
            $table->integer('number_of_segments')->nullable();
            $table->integer('quantity');

            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();

            $table->foreignIdFor(Brand::class, 'brand_id')->nullable();
            $table->foreignIdFor(Category::class, 'category_id')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class,'deleted_by')->nullable();
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