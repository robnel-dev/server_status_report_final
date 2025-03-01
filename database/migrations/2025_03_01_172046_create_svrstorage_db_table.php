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
        Schema::create('svrstorage_db', function (Blueprint $table) {
            $table->id();
            $table->string('svrip'); // Server IP
            $table->string('server_name');
            $table->string('drvletter'); // Drive letter (C, D, etc.)
            $table->integer('drvsizetotal'); // Total disk size (e.g., 500)
            $table->string('drvsize_free'); // Free space (e.g., "100GB")
            $table->date('datecrt'); // Date recorded
            $table->time('timecrt'); // Time recorded
            $table->boolean('svrstat')->default(1); // Status (1 = active)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('svrstorage_db');
    }
};
