<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingInstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('exporter', 255);
            $table->string('att', 255);
            $table->unsignedBigInteger('imports_id');
            $table->string('volumes', 255);
            $table->string('ship', 255);
            $table->unsignedBigInteger('harbors_id');
            $table->date('data');
            $table->longText('invoices')->nullable();
            $table->decimal('thc', 8, 2)->nullable();
            $table->string('BL', 255)->nullable();
            $table->decimal('office_fee', 8, 2)->nullable();
            $table->decimal('doc_bank', 8, 2)->nullable();
            $table->string('sda', 255)->nullable();
            $table->string('origem', 255)->nullable();
            $table->string('divergence')->nullable();
            $table->string('transport_docs', 255)->nullable();
            $table->string('discount_installment', 255)->nullable();
            $table->string('ship_transfer', 255)->nullable();
            $table->string('installment_loan', 255)->nullable();
            $table->unsignedBigInteger('banks_id');
            $table->string('enterprise_name', 255)->nullable();
            $table->unsignedBigInteger('enterprise_cnpj')->nullable();
            $table->timestamps();

            $table->foreign('imports_id')->references('id')->on('imports')->onDelete('cascade');
            $table->foreign('harbors_id')->references('id')->on('harbors')->onDelete('cascade');
            $table->foreign('banks_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_instructions');
    }
}