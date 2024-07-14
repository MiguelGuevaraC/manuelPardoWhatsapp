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
        Schema::create('whatsapp_sends', function (Blueprint $table) {

            $table->id();
            $table->string('number')->nullable();
            $table->string('userResponsability')->nullable();
            $table->string('namesStudent')->nullable();
            $table->string('dniStudent')->nullable();
            $table->string('namesParent')->nullable();
            $table->text('infoStudent')->nullable();
            $table->string('telephone')->nullable();
            $table->text('description')->nullable();
            $table->string('conceptSend')->nullable();
            $table->decimal('paymentAmount', 10, 2)->nullable();
            $table->date('expirationDate')->nullable()->nullable();
            $table->string('cuota')->nullable();
            $table->string('status')->nullable();
            $table->boolean('state')->default(true);
            $table->timestamps();
            $table->softDeletes();
            // Foreign keys
            $table->foreignId('student_id')->nullable()->unsigned()->constrained('people');
            $table->foreignId('user_id')->nullable()->unsigned()->constrained('users');
            $table->foreignId('comminment_id')->nullable()->unsigned()->constrained('compromisos');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_sends');
    }
};
