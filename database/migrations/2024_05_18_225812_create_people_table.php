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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('typeofDocument');
            $table->string('documentNumber')->unique()->nullable();
            $table->string('identityNumber')->nullable();

            $table->string('names')->nullable();
            $table->string('fatherSurname')->nullable();
            $table->string('motherSurname')->nullable();
            $table->string('businessName')->nullable();
            $table->string('level')->nullable();
            $table->string('grade')->nullable();
            $table->string('section')->nullable();
            $table->string('email')->nullable();

            $table->string('origin')->nullable();
            $table->string('ocupation')->nullable();

            $table->string('representativeDni')->nullable();
            $table->string('representativeNames')->nullable();
            $table->string('telephone')->nullable();
            $table->string('status')->nullable();
            $table->boolean('state')->nullable();

            $table->foreignId('user_id')->nullable()->unsigned()->constrained('users');

            $table->foreignId('migration_id')->nullable()->unsigned()->constrained('migration_exports');

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
        Schema::dropIfExists('people');
    }
};
