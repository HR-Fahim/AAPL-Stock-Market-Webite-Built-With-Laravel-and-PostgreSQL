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
        Schema::create('stock_values', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('close');
            $table->decimal('volume', 18, 2); // Adjust the precision and scale accordingly
            $table->float('ex_dividend');
            $table->float('split_ratio');
            $table->float('adj_open');
            $table->float('adj_high');
            $table->float('adj_low');
            $table->float('adj_close');
            $table->decimal('adj_volume', 18, 2); // Adjust the precision and scale accordingly
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
        Schema::dropIfExists('stock_values');
    }
};
