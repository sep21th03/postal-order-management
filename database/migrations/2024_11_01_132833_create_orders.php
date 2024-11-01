<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class  extends Migration
{
    public function up()
    {
        
        Schema::create('parcel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            
            $table->boolean('is_fragile')->default(false);
            $table->boolean('is_high_value')->default(false);
            $table->boolean('is_perishable')->default(false);
            $table->boolean('is_dangerous')->default(false);
            $table->boolean('is_flammable')->default(false);
            $table->boolean('is_cold_storage')->default(false);
            $table->boolean('is_international')->default(false);
            $table->boolean('is_non_standard')->default(false);
            
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('sender_name');
            $table->string('sender_address');
            $table->string('phone_number_sender');
            $table->string('recipient_name');
            $table->string('recipient_address');
            $table->string('phone_number_recipient');
            $table->string('email_recipient');
            $table->string('note');
            $table->foreignId('parcel_type_id')->nullable()->constrained('parcel_types')->onDelete('set null');
            $table->string('item_name')->nullable();
            $table->decimal('value', 12, 2)->default(0);
            $table->decimal('weight', 8, 2);
            $table->enum('status', [
                'pending', 
                'processing', 
                'in_transit', 
                'delivered', 
                'cancelled'
            ])->default('pending');
            $table->enum('payment_status', [
                'paid', 
                'cash_on_delivery' 
            ])->default('cash_on_delivery');
            $table->enum('shipping_status', [
                'standard', 
                'express', 
            ])->default('standard');
            $table->string('tracking_number')->unique();
            $table->timestamps();
        });
        Schema::create('order_parcel_types', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('parcel_type_id')->constrained()->onDelete('cascade');
            $table->primary(['order_id', 'parcel_type_id']);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['parcel_type_id', 'item_name', 'value', 'weight', 'status', 'tracking_number']);
        });
        
        Schema::dropIfExists('order_parcel_types');
        Schema::dropIfExists('parcel_types');
    }
};
