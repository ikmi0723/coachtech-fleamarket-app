<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnsToUsersTable extends Migration
{
    /**
     * マイグレーション実行時
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 郵便番号
            $table->string('postcode')->nullable()->after('email');

            // 住所
            $table->string('address')->nullable()->after('postcode');

            // 建物名
            $table->string('building')->nullable()->after('address');
        });
    }

    /**
     * ロールバック時
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postcode', 'address', 'building']);
        });
    }
}
