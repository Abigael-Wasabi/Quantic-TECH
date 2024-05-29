<?php
//pivot table linking orders n prods
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration// a way to create a new migration
{
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();//primary key
            $table->foreignId('order_id')->constrained()->onDelete('cascade');//fk links to order table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');// fk links to product table
            $table->integer('quantity');//store no of units of prod in the order
            $table->timestamps();//adds created at n updated at
        });
    }

    public function down(): void //down method reverts changes made in up method
    {
        Schema::dropIfExists('order_product');//drops tale if it exists
    }
};
