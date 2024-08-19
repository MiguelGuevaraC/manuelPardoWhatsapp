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
        Schema::create('message_whasapps', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();

            $table->text('block1')->nullable();
            $table->text('block2')->nullable();
            $table->text('block3')->nullable();
            $table->text('block4')->nullable();

            $table->boolean('state')->default(true);
            $table->timestamps();
            $table->foreignId('responsable_id')->nullable()->unsigned()->constrained('people');
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
        Schema::dropIfExists('message_whasapps');
    }
};
