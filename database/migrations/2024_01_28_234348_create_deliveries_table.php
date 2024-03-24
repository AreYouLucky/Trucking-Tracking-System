<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->timestamps();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('driver_id')->on('drivers')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->string('reciever_name')->nullable();
            $table->string('reciever_no',12)->nullable();

            $table->integer('is_delivered')->default(0);
            $table->decimal('from_lat',10,7)->nullable();
            $table->decimal('from_long',10,7)->nullable();
            $table->string('from_province')->nullable();
            $table->string('from_city')->nullable();
            $table->string('from_barangay')->nullable();
            $table->string('from_street')->nullable();

            $table->decimal('to_lat',10,7)->nullable();
            $table->decimal('to_long',10,7)->nullable();

            $table->decimal('driver_lat',10,7)->nullable();
            $table->decimal('driver_long',10,7)->nullable();
            $table->string('to_province')->nullable();
            $table->string('to_city')->nullable();
            $table->string('to_barangay')->nullable();
            $table->string('to_street')->nullable();

            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('proof_path')->nullable();
            $table->double('delivery_weight')->nullable();
            $table->string('delivery_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
