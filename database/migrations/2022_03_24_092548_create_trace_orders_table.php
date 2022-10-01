<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trace_orders', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('details')->nullable();
            $table->foreignId("order_id")->nullable()->constrained("orders")->onDelete("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("admins")->onDelete("set NULL");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trace_orders');
    }
}
