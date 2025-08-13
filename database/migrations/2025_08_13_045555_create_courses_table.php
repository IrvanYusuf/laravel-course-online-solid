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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->integer('duration')->comment('Duration in minutes');
            $table->timestamps();


            // foreign key
            $table->foreignUuid('instructor_id')->constrained("users")->onDelete("cascade");
            $table->foreignUuid('category_id')->constrained("categories")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
