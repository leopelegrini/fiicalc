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
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id();
			$table->date('data');
			$table->unsignedTinyInteger('operacao'); // 0: Compra, 1: Venda
            $table->integer('qtd');
            $table->double('preco_unitario_bruto', 16, 8);
			$table->double('preco_total_bruto', 16, 8);

			$table->decimal('taxas', 12, 8);
			$table->double('preco_unitario_liquido', 16, 8);
			$table->double('preco_total_liquido', 16, 8);

			$table->double('preco_unitario_mp', 16, 4);
			$table->integer('qtd_acumulada');

			$table->double('saldo_venda', 16, 8)->nullable();

			$table->unsignedBigInteger('negociacao_id');
            $table->unsignedBigInteger('ativo_id')->index();
            $table->unsignedBigInteger('apuracao_id')->nullable();
            //$table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

			$table->foreign('negociacao_id')
                ->references('id')->on('negociacoes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lancamentos');
    }
};
