<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_devices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('user_devices_ibfk_1');
			$table->enum('device_type', array('ios','android'))->nullable();
			$table->string('device_token')->nullable()->comment('Device Token for Notifications');
			$table->text('auth_token', 65535)->nullable()->comment('JWT Auth token');
			$table->char('2fa', 6)->nullable()->comment('2FA Token');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_devices');
	}

}
