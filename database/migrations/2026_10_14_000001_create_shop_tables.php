<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
 Schema::create('users',function(Blueprint $t){$t->id();$t->string('name');$t->string('email')->unique();$t->string('password');$t->string('role')->default('user');$t->rememberToken();$t->timestamps();});
 Schema::create('categories',function(Blueprint $t){$t->id();$t->string('name');$t->string('slug')->unique();$t->text('description')->nullable();$t->timestamps();});
 Schema::create('products',function(Blueprint $t){$t->id();$t->foreignId('category_id')->constrained()->restrictOnDelete();$t->string('name');$t->string('slug')->unique();$t->text('description')->nullable();$t->string('image_path')->nullable();$t->unsignedTinyInteger('image_index')->default(1);$t->boolean('is_active')->default(true);$t->timestamps();});
 Schema::create('product_variants',function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->string('sku')->unique();$t->string('size',20);$t->string('color',50);$t->unsignedBigInteger('price');$t->unsignedInteger('stock')->default(0);$t->boolean('is_active')->default(true);$t->timestamps();});
 Schema::create('cart_items',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();$t->unsignedInteger('quantity');$t->unique(['user_id','variant_id']);$t->timestamps();});
 Schema::create('orders',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->restrictOnDelete();$t->uuid('checkout_token')->unique();$t->string('recipient_name');$t->string('phone',20);$t->text('address');$t->string('payment_method')->default('cod');$t->string('status')->default('pending');$t->unsignedBigInteger('total_amount');$t->timestamps();});
 Schema::create('order_items',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->foreignId('variant_id')->constrained('product_variants')->restrictOnDelete();$t->string('product_name');$t->string('sku');$t->string('size',20);$t->string('color',50);$t->unsignedBigInteger('unit_price');$t->unsignedInteger('quantity');$t->unsignedBigInteger('line_total');$t->timestamps();});
 Schema::create('reviews',function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->string('user_name');$t->unsignedTinyInteger('rating');$t->text('comment');$t->timestamps();});
 }
 public function down():void {foreach(['reviews','order_items','orders','cart_items','product_variants','products','categories','users'] as $table) Schema::dropIfExists($table);}
};
