<?php
/**
 * Created by Alaa mohammed.
 * User: alaa
 * Date: 23/07/19
 * Time: 05:31 م
 */

namespace Alaame\Setting\Services;


use Alaame\Setting\ContentTypes\Coordinates;
use Alaame\Setting\ContentTypes\Checkbox;
use Alaame\Setting\ContentTypes\File;
use Alaame\Setting\ContentTypes\Text;
use Alaame\Setting\ContentTypes\Timestamp;
use Alaame\Setting\ContentTypes\Image as ContentImage;
use Alaame\Setting\ContentTypes\SelectMultiple;
use Alaame\Setting\ContentTypes\Relationship;
use Alaame\Setting\ContentTypes\Password;
use Alaame\Setting\ContentTypes\MultipleImage;
use Alaame\Setting\ContentTypes\MultipleCheckbox;
use Alaame\Setting\Repositories\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


/**
 * Class SettingService
 * @package App\Modules\Admin\Services
 */
class SettingService extends Service
{
    private $setting;

    public function __construct()
    {
        $this->setting = $this->repository(SettingRepository::class);
    }

    public function getSettingOrdered()
    {
        return $this->setting->ordered();
    }

    public function getDistinctGroups()
    {
        return $this->setting->getDistinctGroups();
    }

    public function getCountOfKey(string $key)
    {
        return $this->setting->getCountOfKey($key);
    }

    public function getLastSetting()
    {
        return $this->setting->getLastSetting();
    }

    public function createSetting(Request $request)
    {
        $key = implode('.', [Str::slug($request->input('group')), $request->input('key')]);

        $key_check = $this->getCountOfKey($key);

        if ($key_check > 0) {
            return null;
        }

        $lastSetting = $this->getLastSetting();

        if (is_null($lastSetting)) {
            $order = 0;
        } else {
            $order = intval($lastSetting->getAttribute('order')) + 1;
        }

        $request->merge(['order' => $order]);
        $request->merge(['value' => '']);
        $request->merge(['key' => $key]);

        return $this->setting->create($request->except('setting_tab'));
    }

    public function getAllSettings()
    {
        return $this->setting->all();
    }

    public function getSetting($id)
    {
        return $this->setting->find($id);
    }

    public function getPreviousSetting($swapOrder, $group)
    {
        return $this->setting->getPreviousSetting($swapOrder, $group);
    }

    public function getNextSetting($swapOrder, $group)
    {
        return $this->setting->getNextSetting($swapOrder, $group);
    }

    public function updateSettings(Request $request)
    {
        $settings = $this->getAllSettings();

        foreach ($settings as $setting) {
            $groupName = $request->input(str_replace('.', '_', $setting->getAttribute('key')) . '_group');
            if ($groupName != null && $groupName != '') {
                $content = $this->getContentBasedOnType($request, 'settings', (object)[
                    'type' => $setting->getAttribute('type'),
                    'field' => str_replace('.', '_', $setting->getAttribute('key')),
                    'group' => $setting->getAttribute('group'),
                ], $setting->getAttribute('details'));

                if ($setting->getAttribute('type') == 'image' && $content == null) {
                    continue;
                }

                if ($setting->getAttribute('type') == 'file' && $content == json_encode([])) {
                    continue;
                }

                $key = preg_replace('/^' . Str::slug($setting->getAttribute('group')) . './i', '', $setting->getAttribute('key'));

                $setting->setAttribute('group', $groupName);
                $setting->setAttribute('key', implode('.', [Str::slug($setting->group), $key]));
                $setting->setAttribute('value', $content);
                $setting->save();
            }
        }

    }

    protected function getContentBasedOnType(Request $request, $slug, $row, $options = null)
    {
        switch ($row->type) {
            case 'password':
                return (new Password($request, $slug, $row, $options))->handle();
            case 'checkbox':
                return (new Checkbox($request, $slug, $row, $options))->handle();
            case 'multiple_checkbox':
                return (new MultipleCheckbox($request, $slug, $row, $options))->handle();
            case 'file':
                return (new File($request, $slug, $row, $options))->handle();
            case 'multiple_images':
                return (new MultipleImage($request, $slug, $row, $options))->handle();
            case 'select_multiple':
                return (new SelectMultiple($request, $slug, $row, $options))->handle();
            case 'image':
                return (new ContentImage($request, $slug, $row, $options))->handle();
            case 'timestamp':
                return (new Timestamp($request, $slug, $row, $options))->handle();
            case 'coordinates':
                return (new Coordinates($request, $slug, $row, $options))->handle();
            case 'relationship':
                return (new Relationship($request, $slug, $row, $options))->handle();
            default:
                return (new Text($request, $slug, $row, $options))->handle();
        }
    }

    public function prepareSettings()
    {
        $data = $this->getSettingOrdered();

        $settings = [];

        $settings[__('ame-setting::settings.group_general')] = [];

        foreach ($data as $d) {
            if ($d->getAttribute('group') == '' || $d->getAttribute('group') == __('ame-setting::settings.group_general')) {
                $settings[__('ame-setting::settings.group_general')][] = $d;
            } else {
                $settings[$d->group][] = $d;
            }
        }

        if (count($settings[__('ame-setting::settings.group_general')]) == 0) {
            unset($settings[__('ame-setting::settings.group_general')]);
        }

        return $settings;
    }

    public function prepareSettingsGroups()
    {
        $groups_data = $this->getDistinctGroups();

        $groups = [];

        foreach ($groups_data as $group) {
            if ($group->getAttribute('group') != '') {
                $groups[] = $group->getAttribute('group');
                if (!request()->session()->has('setting_tab')) {
                    if (Auth::user()->can('ame-setting::settings..settings_tabs.'. strtolower($group->getAttribute('group')))) {
                        request()->session()->flash('setting_tab', ($group->getAttribute('group')));
                    }
                }
            }
        }
        return $groups;
    }

}
