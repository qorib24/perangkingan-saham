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
        Schema::create('saham_alternatifs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_saham', 10)->unique();
            $table->string('nama_perusahaan', 255);
            $table->string('sektor', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saham_alternatifs');
    }
};
