<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(){
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->enum('return_reason', ['Leaving Company', 'Exchange', 'Broken', 'Upgrade', 'Other']);
            $table->text('equipment_condition');
            $table->text('missing_parts')->nullable();
            $table->datetime('return_date');
            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Rejected'])->default('Pending');
            $table->boolean('admin_verified')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('return_requests');
    }
};