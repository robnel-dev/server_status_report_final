<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection to use.
     *
     * @var string
     */
    protected $connection = 'mysql'; // 👈 This tells Laravel to use the "mysql" connection

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('physical_checks', function (Blueprint $table) {
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
        Schema::connection($this->connection)->dropIfExists('physical_checks');
    }
};
