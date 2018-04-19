<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHospitalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hospitals', function(Blueprint $table)
		{
			$table->increments('id');
			$table->enum('type', array('hospital','health_care'))->default('hospital');
			$table->string('title', 200);
			$table->text('description', 65535)->nullable();
			$table->string('address');
			$table->string('location', 100);
			$table->string('zip_code', 10)->index('zip_code');
			$table->decimal('latitude', 11, 9)->nullable()->default(0.000000000);
			$table->decimal('longitude', 11, 9)->nullable()->default(0.000000000);
			$table->time('timing_open')->nullable();
			$table->time('timing_close')->nullable();
			$table->string('phone', 20)->nullable();
			$table->boolean('is_24_7_phone')->default(0);
			$table->boolean('is_active')->default(1);
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
		Schema::drop('hospitals');
	}

}
