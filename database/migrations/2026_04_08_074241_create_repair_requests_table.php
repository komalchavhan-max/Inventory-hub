<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->text('issue_description');
            $table->enum('urgency', ['Critical', 'High', 'Medium', 'Low'])->default('Medium');
            $table->string('location')->nullable();
            $table->boolean('photos_available')->default(false);
            $table->enum('status', ['Pending', 'In-Review', 'Approved', 'Completed', 'Rejected'])->default('Pending');
            $table->text('admin_notes')->nullable();
            $table->datetime('request_date');
            $table->datetime('completion_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_requests');
    }
};