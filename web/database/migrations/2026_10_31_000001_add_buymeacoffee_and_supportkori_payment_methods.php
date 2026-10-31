<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buy Me a Coffee
        $bmcExists = DB::table('payment_methods')
            ->where('type', 'buymeacoffee')
            ->first();

        if (!$bmcExists) {
            $bmcId = DB::table('payment_methods')->insertGetId([
                'name'       => 'Buy Me a Coffee',
                'type'       => 'buymeacoffee',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('settings')->insert([
                'name'             => 'api_key',
                'value'            => 'https://buymeacoffee.com/riff1431',
                'context'          => 'buymeacoffee',
                'settingable_type' => 'App\\Models\\Quran\\Payment\\PaymentMethod',
                'settingable_id'   => $bmcId,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // 2. Support Kori
        $skExists = DB::table('payment_methods')
            ->where('type', 'supportkori')
            ->first();

        if (!$skExists) {
            $skId = DB::table('payment_methods')->insertGetId([
                'name'       => 'Support Kori',
                'type'       => 'supportkori',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('settings')->insert([
                'name'             => 'api_key',
                'value'            => 'https://www.supportkori.com/arifurfullstack',
                'context'          => 'supportkori',
                'settingable_type' => 'App\\Models\\Quran\\Payment\\PaymentMethod',
                'settingable_id'   => $skId,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $methodIds = DB::table('payment_methods')
            ->whereIn('type', ['buymeacoffee', 'supportkori'])
            ->pluck('id');

        DB::table('settings')
            ->where('settingable_type', 'App\\Models\\Quran\\Payment\\PaymentMethod')
            ->whereIn('settingable_id', $methodIds)
            ->delete();

        DB::table('payment_methods')
            ->whereIn('type', ['buymeacoffee', 'supportkori'])
            ->delete();
    }
};
