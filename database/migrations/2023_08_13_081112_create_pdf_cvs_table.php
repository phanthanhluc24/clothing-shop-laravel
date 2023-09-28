<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('pdf_cvs', function (Blueprint $table) {
            $table->integer("id")->autoIncrement();
            $table->string("educations")->nullable();
            $table->longText("experience")->nullable();
            $table->string("skills")->nullable();
            $table->string("hobbies")->nullable();
            $table->longText("objectives")->nullable();
            $table->string("languages")->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_cvs');
    }
};
