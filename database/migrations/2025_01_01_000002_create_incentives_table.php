<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncentivesTable extends Migration
{
    public function up()
    {
        Schema::create('incentives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->decimal('max_amount', 15, 2)->default(0);
            $table->integer('duration_months')->nullable();
            $table->text('conditions')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incentives');
    }
}
