<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
			$table->integer('assign_id');
			$table->string('company_name');
            $table->string('contact_person');
			$table->string('phone');
			$table->string('city');
            $table->integer('business_id');
			$table->integer('requirement_id');
			$table->integer('status_id');
			$table->string('reff');
			$table->longText('remarks');
			$table->string('image');
			$table->date('inquiry_date');
			$table->longText('followup_remarks_1');
			$table->date('followup_date_1');
			$table->longText('followup_remarks_2');
			$table->date('followup_date_2');
			$table->longText('followup_remarks_3');
			$table->date('followup_date_3');
			$table->longText('followup_remarks_4');
			$table->date('followup_date_4');
			$table->longText('followup_remarks_5');
			$table->date('followup_date_5');
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
        Schema::dropIfExists('inquiries');
    }
}
