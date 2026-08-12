<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_satuan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable()->first();
        });


        Schema::table('master_satuan', function (Blueprint $table) { 
            $table->dropPrimary(); 
        });

        DB::statement(" ALTER TABLE master_satuan MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (id) ");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_satuan', function (Blueprint $table) {
            $table->dropPrimary(); 
        });


        Schema::table('master_satuan', function (Blueprint $table) { 
            $table->primary('kd_satuan'); 
            $table->dropColumn('id'); 
        });
    }
};
