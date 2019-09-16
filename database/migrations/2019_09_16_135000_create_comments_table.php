<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键id');
            $table->unsignedInteger('parent_id')->index()->comment('上级评论id，若是一级评论则为0');
            $table->string('nickname', 32)->comment('评论人昵称');
            $table->string('head_pic')->comment('评论人头像');
            $table->text('content')->comment('评论内容');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `comments` comment '评论表'"); //表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
