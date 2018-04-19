<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralStatusHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('referral_status_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('referral_id')->unsigned()->index('referrral_id');
			$table->integer('created_by')->unsigned()->index('created_by')->comment('Employee ID');
			$table->boolean('status')->default(0)->comment('Updated status');
			$table->text('reason', 65535)->nullable()->comment('if status is accepted or rejected than it is must');
			$table->dateTime('created_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('referral_status_histories');
	}

}
