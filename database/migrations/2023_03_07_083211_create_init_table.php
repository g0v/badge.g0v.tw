<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_association', function (Blueprint $table) {
            $table->id('association_id');
            $table->bigInteger('user_id');
            $table->string('login_name');
            $table->timestamps();

            $table->index(['user_id', 'association_id']);
        });

        Schema::create('user_badge', function (Blueprint $table) {
            $table->id('user_badge_id');
            $table->bigInteger('user_id');
            $table->bigInteger('hole_id');
            $table->bigInteger('hole_badge_id');
            $table->timestamp('got_at');
            $table->string('title');
            $table->jsonb('extra');

            $table->index(['user_id', 'hole_id', 'hole_badge_id', 'user_badge_id']);
            $table->index(['hole_id', 'hole_badge_id', 'user_badge_id']);
        });

        Schema::create('hole', function (Blueprint $table) {
            $table->id('hole_id');
            $table->string('hole_name')->unique();
            $table->jsonb('meta');
            $table->timestamps();
        });

        Schema::create('hole_staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->bigInteger('hole_id');
            $table->bigInteger('user_id');
            $table->integer('staff_type');
            $table->timestamps();

            $table->unique(['hole_id', 'user_id']);
        });

        Schema::create('hole_badge', function (Blueprint $table) {
            $table->id('hole_badge_id');
            $table->bigInteger('hole_id');
            $table->jsonb('meta');
            $table->string('title');
            $table->timestamps();

            $table->index(['hole_id', 'hole_badge_id']);
        });

        Schema::create('admin', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->timestamps();

            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_association');
        Schema::dropIfExists('user_badge');

        Schema::dropIfExists('hole');
        Schema::dropIfExists('hole_staff');
        Schema::dropIfExists('hole_badge');

        Schema::dropIfExists('admin');
    }
}
