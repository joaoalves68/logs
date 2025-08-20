<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_scan_details', function (Blueprint $table) {
            $table->enum('classification', [1, 2, 3])->nullable()->after('client_ip');
            $table->text('analysis_reason')->nullable()->after('classification');
        });
    }

    public function down(): void
    {
        Schema::table('log_scan_details', function (Blueprint $table) {
            $table->dropColumn('analysis_reason');
            $table->dropColumn('classification');
        });
    }
};
