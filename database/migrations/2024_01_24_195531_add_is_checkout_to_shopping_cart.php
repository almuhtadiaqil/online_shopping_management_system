<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCheckoutToShoppingCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
            $table->boolean('is_checkout')->default(false);
            $table->timestampTz('checkout_date')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->dropColumn('is_checkout');
            $table->dropColumn('checkout_date');
            $table->dropColumn('quantity');
        });
    }
}
