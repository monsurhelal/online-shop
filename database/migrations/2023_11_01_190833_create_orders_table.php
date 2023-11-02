<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal',10,2);
            $table->double('shiping',10,1);
            $table->string('cupon_code')->nullable();
            $table->double('discount',10,1)->nullable();
            $table->double('grant_total',10,1);

            //user address related colum
            $table->string('fast_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('aparmemt');
            $table->string('city');
            $table->string('status');
            $table->string('zip');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
