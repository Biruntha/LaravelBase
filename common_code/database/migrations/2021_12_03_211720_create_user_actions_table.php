<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user')->unsigned()->index()->nullable();
            $table->foreign('user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
                     
            $table->string('ip')->nullable();
            $table->string('uid')->nullable();

            $table->text('page_url')->nullable();
            $table->string('page_base_url')->nullable();            
            $table->string('action_type')->nullable(); //page view / click etc
            $table->string('source')->nullable();
            $table->text('source_url')->nullable();
            $table->string('source_id')->nullable(); //ad id etc

            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();

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
        Schema::dropIfExists('user_actions');
    }
}
