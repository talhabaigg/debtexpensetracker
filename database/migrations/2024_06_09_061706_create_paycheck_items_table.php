<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaycheckItemsTable extends Migration
{
    public function up()
    {
        Schema::create('paycheck_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paycheck_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('item_amount', 15, 2);
            $table->enum('item_type', ['income', 'expense']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paycheck_items');
    }
}
