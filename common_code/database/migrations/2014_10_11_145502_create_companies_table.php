<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('email');
            $table->string('contact');
            $table->string('contact_secondary');
            $table->text('address')->nullable();

            $table->bigInteger('country')->unsigned()->index()->nullable();
            $table->foreign('country')->references('id')->on('countries')->onDelete('set null');
            
            $table->bigInteger('region')->unsigned()->index()->nullable();
            $table->foreign('region')->references('id')->on('regions')->onDelete('set null');

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
        Schema::dropIfExists('companies');
    }
}
