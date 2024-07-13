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
        Schema::create('compromisos', function (Blueprint $table) {
            $table->id();
            $table->integer('cuotaNumber')->nullable();
            $table->decimal('paymentAmount', 8, 2)->nullable();
            $table->date('expirationDate')->nullable();
            $table->string('conceptDebt')->nullable();
            $table->date('lastMessageDate')->nullable()->nullable();
            $table->string('status')->nullable();
            $table->boolean('state')->nullable()->default(true);
            $table->timestamps();
            $table->foreignId('student_id')->nullable()->unsigned()->constrained('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compromisos');
    }
};
