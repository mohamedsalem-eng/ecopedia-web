<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckedColumnToServiceEnquiryConvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_enquiry_convs', function (Blueprint $table) {
            $table->boolean("checked")->default(true)->after("position");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_enquiry_convs', function (Blueprint $table) {
            $table->dropColumn(["checked"]);
        });
    }
}
