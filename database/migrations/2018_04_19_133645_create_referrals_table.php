<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('referrals', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('hospital_id')->unsigned()->index('hospital_id');
			$table->integer('referred_by')->unsigned()->index('referred_by')->comment('Doctor ID');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('age');
			$table->string('phone');
			$table->text('diagnosis', 65535)->nullable();
			$table->boolean('status')->default(0);
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
		Schema::drop('referrals');
	}

}
