<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned()->default(2)->comment('Default normal user');
			$table->integer('hospital_id')->unsigned()->nullable()->index('hospital_id');
			$table->integer('profession_id')->unsigned()->nullable()->index('profession_id');
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->string('password');
			$table->string('phone', 50)->default('');
			$table->string('address')->default('');
			$table->integer('city')->unsigned()->default(0);
			$table->integer('state')->unsigned()->default(0)->index('state');
			$table->integer('country')->nullable()->default(231);
			$table->string('profile_picture', 30)->default('');
			$table->string('remember_token', 100)->nullable();
			$table->string('email_verification', 100)->default('0');
			$table->string('sms_verification', 6)->default('1');
			$table->boolean('is_active')->default(1)->comment('Fully activated account.');
			$table->char('2fa', 6)->nullable()->default(1)->comment('2FA Code');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
