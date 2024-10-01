<?php

use App\Enums\ArticleStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('text');
            $table->text('image')->nullable();
            $table->integer('views')->default(0);
            $table->tinyInteger('status')
                ->default(ArticleStatusEnum::DRAFT->value);
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
