<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->json('specifications')->nullable()->after('description');
            $table->date('purchase_date')->nullable()->after('category');
            $table->date('warranty_until')->nullable()->after('purchase_date');
            $table->enum('condition', ['New', 'Good', 'Fair', 'Poor'])->default('Good')->after('status');
        });
    }

    public function down()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['description', 'specifications', 'purchase_date', 'warranty_until', 'condition']);
        });
    }
};