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
        Schema::create('service_vpns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('privateKey');
            $table->string('publicKey');
            $table->string('preSharedKey');
            $table->string('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_vpns');
    }
};
