<?php


namespace Alaame\Setting;


use Alaame\Setting\Repositories\SettingRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

class AMESetting
{

    public $setting_cache = null;
    protected $setting_prefix = 'settings_';

    private $services = [
        'setting' => SettingRepository::class,
    ];

    public function webRoutes()
    {
        require __DIR__ . '/Routes/web.php';
    }

    public function apiRoutes()
    {
        require __DIR__ . '/Routes/api.php';
    }

    protected function getInstanceOf($service)
    {
        return app($this->services[$service]);
    }

    /**
     * Copied from voyager package
     * @param $key
     * @param null $default
     * @return null
     */
    public function setting($key, $default = null)
    {
        $globalCache = config('ame-setting.cache', false);

        if ($globalCache && Cache::has($this->setting_prefix . $key)) {
            return Cache::get($this->setting_prefix . $key);
        }

        if ($this->setting_cache === null) {
            foreach ($this->getInstanceOf('setting')->all() as $setting) {
                $keys = explode('.', $setting->key);
                $this->setting_cache[$keys[0]][$keys[1]] = @$setting->value;

                if ($globalCache) {
                    Cache::forever($this->setting_prefix . $setting->key, $setting->value);
                }
            }
        }

        $parts = explode('.', $key);

        if (count($parts) == 2) {
            return @$this->setting_cache[$parts[0]][$parts[1]] ?: $default;
        } else {
            return @$this->setting_cache[$parts[0]] ?: $default;
        }
    }
}
