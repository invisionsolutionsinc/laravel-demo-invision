<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->nullable()->after('phone_number');
            $table->enum('gender', ['Male', 'Female'])->nullable()->after('phone_number');
            $table->text('talent')->nullable()->after('phone_number');
            $table->text('additional_talent')->nullable()->after('phone_number');
            $table->text('interests')->nullable()->after('phone_number');
            $table->boolean('volunteering_interest')->default(0)->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
