<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index()->comment('队列类型');
            $table->longText('payload')->comment('队列执行内容');
            $table->unsignedTinyInteger('attempts')->comment('队列执行次数');
            $table->unsignedInteger('reserved_at')->nullable()->comment('再次执行时间');
            $table->unsignedInteger('available_at')->comment('有效时间');
            $table->unsignedInteger('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
