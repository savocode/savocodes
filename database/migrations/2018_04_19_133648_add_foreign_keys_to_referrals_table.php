<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReferralsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('referrals', function(Blueprint $table)
		{
			$table->foreign('hospital_id', 'referrals_ibfk_1')->references('id')->on('hospitals')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('referred_by', 'referrals_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('referrals', function(Blueprint $table)
		{
			$table->dropForeign('referrals_ibfk_1');
			$table->dropForeign('referrals_ibfk_2');
		});
	}

}
