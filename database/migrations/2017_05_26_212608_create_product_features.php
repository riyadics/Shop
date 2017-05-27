<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->enum('input_type', ['text', 'select'])->default('text');
            $table->enum('product_type', ['item', 'key'])->default('item');
            $table->json('default_values')->nullable();
            $table->json('validation_rules')->nullable();
            $table->json('help_message')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::drop('products_features');
    }
}
