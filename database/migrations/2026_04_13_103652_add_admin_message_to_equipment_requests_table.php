<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(){
        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->text('admin_message')->nullable()->after('admin_notes');
        });
        
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->text('admin_message')->nullable();
        });
        
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->text('admin_message')->nullable();
        });
        
        Schema::table('return_requests', function (Blueprint $table) {
            $table->text('admin_message')->nullable();
        });
    }

    public function down(){
        Schema::table('equipment_requests', function (Blueprint $table) {
            $table->dropColumn('admin_message');
        });
        
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->dropColumn('admin_message');
        });
        
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropColumn('admin_message');
        });
        
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn('admin_message');
        });
    }
};