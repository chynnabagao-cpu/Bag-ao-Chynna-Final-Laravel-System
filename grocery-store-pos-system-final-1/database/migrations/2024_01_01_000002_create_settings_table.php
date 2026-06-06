<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $defaults = [
            ['key' => 'gcash_qr_path', 'value' => null],
            ['key' => 'store_name', 'value' => 'Nicolle Grocery Store'],
            ['key' => 'store_address', 'value' => '123 Market St, Metro Manila'],
            ['key' => 'store_contact', 'value' => '+63 912 345 6789'],
        ];

        foreach ($defaults as $default) {
            DB::table('settings')->insert(array_merge($default, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
