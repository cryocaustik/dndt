<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns')
                ->onDelete('CASCADE');
            $table->string('item');
            $table->string('description');
            $table->integer('quantity');
            $table->dateTime('acquired_at')->useCurrent();
            $table->string('held_by')->nullable();
            $table->boolean('claimed')->default(false);
            $table->string('claimed_by')->nullable();
            $table->boolean('in_bag_of_holding')->default(false);
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
        Schema::dropIfExists('inventories');
    }
}
