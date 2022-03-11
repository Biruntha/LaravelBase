<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('email')->unique();

            $table->bigInteger('company')->unsigned()->index()->nullable();
            $table->foreign('company')->references('id')->on('companies')->onDelete('cascade');
            
            $table->bigInteger('department')->unsigned()->index()->nullable();
            $table->foreign('department')->references('id')->on('departments')->onDelete('set null');
            
            $table->string('role')->nullable(); //ADMIN, MANAGER, CASHIER, STOCK MANAGER
            
            $table->string('password');

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
        Schema::dropIfExists('users');
    }
}
