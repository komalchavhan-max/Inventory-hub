<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(){
        Schema::create('exchange_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('old_equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->foreignId('requested_equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->text('exchange_reason');
            $table->text('old_equipment_condition');
            $table->boolean('has_damage')->default(false);
            $table->text('damage_description')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');
            $table->datetime('request_date');
            $table->datetime('admin_approval_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('exchange_requests');
    }
};