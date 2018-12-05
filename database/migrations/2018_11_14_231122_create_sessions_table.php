<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('sessions', function (Blueprint $table) : void {
            $table->uuid('id');
            $table->text('encryption_payload')->nullable();

            $table->string('token', 1000)->nullable();
            $table->string('type', 1000)->nullable();
            $table->string('webhook', 1000)->nullable();

            $table->timestamp('expires_in');
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('sessions');
    }
}
