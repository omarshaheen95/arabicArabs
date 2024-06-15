<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\Setting;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Factory $cache)
    {
        $settings = [
            ['name' => 'Logo', 'key' => 'logo', 'value' => null, 'type' => 'file'],
            ['name' => 'Logo Min', 'key' => 'logo_min', 'value' => null, 'type' => 'file'],
            ['name' => 'Name', 'key' => 'name', 'value' => null, 'type' => 'text'],
            ['name' => 'Mobile', 'key' => 'mobile', 'value' => null, 'type' => 'text'],
            ['name' => 'Email', 'key' => 'email', 'value' => null, 'type' => 'text'],
            ['name' => 'Whatsapp', 'key' => 'whatsapp', 'value' => null, 'type' => 'text'],
            ['name' => 'Facebook', 'key' => 'facebook', 'value' => null, 'type' => 'text'],
            ['name' => 'Twitter', 'key' => 'twitter', 'value' => null, 'type' => 'text'],
            ['name' => 'Instagram', 'key' => 'instagram', 'value' => null, 'type' => 'text'],
            ['name' => 'Linkedin', 'key' => 'linkedin', 'value' => null, 'type' => 'text'],
            ['name' => 'Youtube', 'key' => 'youtube', 'value' => null, 'type' => 'text'],


        ];

        foreach ($settings as $setting) {
            $sets = Setting::query()->firstOrCreate(
                [
                    'key' => $setting['key'],
                ],
                [
                    'name' => $setting['name'],
                    'type' => $setting['type'],
                    'value' => $setting['value']
                ]
            );
        }

        $settings = Cache::remember('settings', 60, function () use ($settings) {
            $settings = Setting::query()->get();
            // Laravel >= 5.2, use 'lists' instead of 'pluck' for Laravel <= 5.1
            return $settings->pluck('value', 'key')->all();
        });
        config()->set('settings', $settings);

    }
}
