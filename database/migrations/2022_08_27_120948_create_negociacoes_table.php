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
        Schema::create('negociacoes', function (Blueprint $table) {
            $table->id();
			$table->date('data');
			$table->string('numero', 30);
			$table->decimal('valor', 10, 2);
            $table->decimal('taxas', 6, 2);
            //$table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negociacoes');
    }
};
