<?php

namespace Tal7aouy\Settings;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Tal7aouy\Settings\Models\Setting;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
       $this->loadMigrationsFrom(__DIR__."/database/migrations");
        // only use the Settings package if the Settings table is present in the database
        if (!\App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            $settings = Setting::all();
            foreach ($settings as $key => $setting)
            {
                Config::set('settings.'.$setting->key, $setting->value);
            }
        }
    }

    public function register()
    {
        $this->app->bind('settings', function ($app) {
            return new Setting();
        });
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Setting', Setting::class);
    }
}
