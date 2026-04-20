<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(){
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->text('issue_description');
            $table->decimal('cost', 10, 2);
            $table->string('technician_name');
            $table->date('repair_date');
            $table->timestamps(); 
        });
    }

    public function down(){
        Schema::dropIfExists('maintenance_logs');
    }
};