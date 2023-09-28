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
        Schema::create('products', function (Blueprint $table) {
            $table->integer("id")->autoIncrement();
            $table->integer("user_id");
            $table->integer("category_id");
            $table->string("title");
            $table->longText("desc");
            $table->string("image");
            $table->integer("quantity");
            $table->decimal("new_price",10,2);
            $table->decimal("old_price",10,2);
            $table->foreign("category_id")->references("id")->on("categories");
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('products');
    }
};
