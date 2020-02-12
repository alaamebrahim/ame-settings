<?php

namespace Alaame\Setting;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->registerConfig();
        }
    }

    public function register()
    {
        $this->registerCommands();

        $this->app->bind('AMESetting', function (){
            return new AMESetting();
        });

    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');

        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'ame-setting');

        $this->loadTranslationsFrom(__DIR__ . '/Resources/lang', 'ame-setting');

        $this->mergeConfigFrom(__DIR__ . '/Config/ame-setting.php', 'ame-setting');

        $this->registerBladeDirective();
    }

    private function registerCommands()
    {}

    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/Config/ame-setting.php' => config_path('ame-setting.php')
        ], 'ame-setting');
    }

    protected function registerBladeDirective(){
        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });
    }
}
