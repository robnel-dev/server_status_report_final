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
        Schema::create('physical_checks', function (Blueprint $table) {
            $table->id();
            $table->string('in_charge'); // Person responsible
            $table->enum('aircon_status', ['Normal', 'Faulty']);
            $table->boolean('amber_alert'); // Yes/No
            $table->text('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_checks');
    }
};
