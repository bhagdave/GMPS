<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOAuthUserIdFieldsToVarChar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->string('user_id', 100)->change();
        });
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->string('user_id', 100)->change();
        });
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->string('user_id', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
    }
}
