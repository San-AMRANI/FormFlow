<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->string('email');
            $table->string('session_token');
            $table->string('ip_address');
            $table->timestamps();

            $table->unique(['form_id', 'session_token', 'ip_address', 'email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_responses');
    }
}
