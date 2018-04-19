<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReferralStatusHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('referral_status_histories', function(Blueprint $table)
		{
			$table->foreign('created_by', 'referral_status_histories_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('referral_id', 'referral_status_histories_ibfk_2')->references('id')->on('referrals')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('referral_status_histories', function(Blueprint $table)
		{
			$table->dropForeign('referral_status_histories_ibfk_1');
			$table->dropForeign('referral_status_histories_ibfk_2');
		});
	}

}
