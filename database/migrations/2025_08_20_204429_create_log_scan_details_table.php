<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_scan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('log_scan_id');
            $table->timestamp('timestamp');
            $table->string('domain');
            $table->string('client_ip');
            $table->timestamps();

            $table->foreign('log_scan_id')
                ->references('id')
                ->on('log_scans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_scan_details');
    }
};
