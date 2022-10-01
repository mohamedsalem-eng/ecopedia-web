<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceEnquiryConvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_enquiry_convs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("service_enquiry_id")->constrained("service_enquiries");
            $table->foreignId("admin_id")->nullable()->constrained("admins")->onDelete("cascade");
            $table->text("customer_message")->nullable();
            $table->text("admin_message")->nullable();
            $table->integer("position")->default(0);
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
        Schema::dropIfExists('service_enquiry_convs');
    }
}
