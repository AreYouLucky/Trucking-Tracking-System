<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->bigInteger('driver_id');
            $table->date('schedule_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
