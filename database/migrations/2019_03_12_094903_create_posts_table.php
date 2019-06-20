<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // todo string，1个长度可以存一个字母或一个数字或一个汉字
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title", 32)->comment('标题');
            $table->text("content")->comment('内容');
            $table->smallInteger('view_count')->unsigned()->default(0)->comment('浏览量');
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
        Schema::dropIfExists('posts');
    }
}
