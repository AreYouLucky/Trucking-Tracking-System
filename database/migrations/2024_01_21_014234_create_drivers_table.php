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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id('driver_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('lname', 100)->nullable();
            $table->string('fname', 100)->nullable();
            $table->string('mname', 100)->nullable();
            $table->string('suffix', 100)->nullable();
            $table->string('sex', 100)->nullable();
            $table->string('contact_no', 50)->nullable();
            $table->string('license_id')->unique();
            $table->string('pic_path')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();
            $table->boolean('is_available')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
