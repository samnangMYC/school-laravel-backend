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
        Schema::create('teachers', function (Blueprint $table) {
        $table->id();
        $table->string('fname');
        $table->string('lname');
        $table->unsignedBigInteger('department_id');
        $table->string('phone')->nullable();
        $table->date('dob')->nullable();
        $table->enum('gender', ['Male', 'Female', 'Other']);
        $table->string('email')->unique();
        $table->date('join_date')->nullable();
        $table->text('education')->nullable();
        $table->text('description')->nullable();
        $table->enum('status', ['Active', 'Inactive'])->default('Active');
        $table->string('image')->nullable();
        $table->timestamps();
        $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};
