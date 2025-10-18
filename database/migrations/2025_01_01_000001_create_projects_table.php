<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('sector')->nullable();
            $table->decimal('investment_amount', 15, 2)->default(0);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
