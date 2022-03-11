<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user')->unsigned()->index();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');

            $table->string('activity_type')->nullable();
            $table->string('activity_description')->nullable();
            $table->string('order_id')->nullable();
            $table->string('activity_entity_id')->nullable();
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
        Schema::dropIfExists('activity_logs');
    }
}
