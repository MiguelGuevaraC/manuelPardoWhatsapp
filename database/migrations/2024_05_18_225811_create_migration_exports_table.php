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
        Schema::create('migration_exports', function (Blueprint $table) {

            $table->id();
            $table->string('number', 255);
            $table->string('type', 255);
            $table->string('comment', 255);
            $table->string('routeExcel', 255);

            $table->boolean('state')->default(true);
            $table->foreignId('user_id')->nullable()->unsigned()->constrained('users');

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
        Schema::dropIfExists('migration_exports');
    }
};
