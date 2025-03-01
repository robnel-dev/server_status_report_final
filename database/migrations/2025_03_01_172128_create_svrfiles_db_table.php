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
        Schema::create('svrfiles_db', function (Blueprint $table) {
            $table->id();
            $table->string('svrip');
            $table->string('filename');
            $table->integer('filesize'); // Size in bytes
            $table->date('datecrt');
            $table->time('timecrt');
            $table->boolean('svrstat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('svrfiles_db');
    }
};
