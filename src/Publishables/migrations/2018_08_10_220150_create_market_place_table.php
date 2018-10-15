<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('location');
            $table->text('permissions')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('remember_token')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('permissions');
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->primary(['user_id', 'role_id']);
        });
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->string('name')->unique();
            $table->text('description');
            $table->integer('price');
            $table->integer('user_id')->unsigned();
            // if (config('product.review')) {
            $table->date('reviewed_at')->nullable();
            $table->integer('reviewed_by')->unsigned()->nullable();
            // }
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('product_category_id')->references('id')->on('categories')->onDelete('cascade');
            if (config('market.product.review')) {
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('cascade');
            }
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->unique();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('categories');
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('product_id')->references('id')->on('products');
        });
        Schema::create('product_variation_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->integer('stock');
            $table->integer('price')->nullable();
            $table->timestamps();
        });
        Schema::create('product_variations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->integer('product_variation_type_id')->unsigned()->index();
            $table->text('details');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->foreign('product_variation_type_id')->references('id')->on('product_variation_types');

            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('product_variation_type_id')->unsigned();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variation_type_id')->references('id')->on('product_variation_types')->onDelete('cascade');
        });
        Schema::create('cart_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('cart_id')->unsigned();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');

        });
        Schema::create('wishlists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('product_variation_type_id')->unsigned();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreign('product_variation_type_id')->references('id')->on('product_variation_types')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        Schema::create('user_wishlist', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('wishlist_id')->unsigned();
            $table->foreign('wishlist_id')->references('id')->on('wishlists')->onDelete('cascade');

        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 32);
            $table->integer('user_id')->unsigned();
            $table->integer('amount')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->float('percentage')->default(0);
            $table->timestamps();
        });
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('user_coupon', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('coupon_id')->unsigned();
            $table->boolean('purchased')->default(false);
            $table->primary(['user_id', 'coupon_id']);
        });
        Schema::table('user_coupon', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->float('percentage');
            $table->integer('saleable_id')->unsigned();
            $table->string('saleable_type');
            $table->boolean('active')->default(true);
            $table->integer('user_id')->unsigned();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('users');
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_category_id']);
        });

        Schema::dropIfExists('products');

        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('product_variation_types');
        Schema::table('product_variations', function($table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_variation_type_id']);
        });
        Schema::dropIfExists('product_variations');
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('cart_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cart_id']);
        });
        Schema::dropIfExists('carts');
        Schema::dropIfExists('cart_user');

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_variation_type_id']);
        });
        Schema::table('user_wishlist', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['wishlist_id']);
        });
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('user_wishlist');



        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('user_coupon', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['coupon_id']);
        });
        Schema::dropIfExists('user_coupon');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('sales');


    }
}
