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
        Schema::create('apuracoes', function (Blueprint $table) {
            $table->id();
			$table->unsignedInteger('ano_mes');
			$table->decimal('base_calculo', 10, 4);
			$table->decimal('aliquota', 8, 4);
			$table->decimal('valor_apurado', 10, 4);
			$table->unsignedTinyInteger('status'); // 0: Aberto, 1: Pago
			//$table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('apuracoes');
    }
};
