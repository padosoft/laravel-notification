<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('mynotifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descr')->index();
            $table->string('class')->index();
            $table->boolean('active')->default('0')->index();
            $table->boolean('audit_active')->default('0')->index();
            $table->timestamps();
        });

        Schema::create('mynotifications_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mynotifications_ID')->index();
            $table->string('users_ID')->index();
            $table->text('methods');
            $table->timestamps();
        });

        Schema::create('mynotifications_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mynotifications_ID')->index();
            $table->string('roles_ID')->index();
            $table->text('methods');
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
        //
        Schema::dropIfExists('mynotifications');
        Schema::dropIfExists('mynotifications_users');
        Schema::dropIfExists('mynotifications_roles');
    }
}
