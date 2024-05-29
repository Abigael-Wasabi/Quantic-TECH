<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration// a way to create a new migration
{
    public function up()//defines changes to b made in db
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->text('address');
            $table->timestamps();
        });
    }

    public function down(): void//reverts changes in up method
    {
        Schema::dropIfExists('customers');//drops tale if it exists
    }
};
