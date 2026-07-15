<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('variations')->nullable()->after('description');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->string('variation')->nullable()->after('product_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->string('variation')->nullable()->after('product_name');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('variations');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('variation');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('variation');
        });
    }
};
