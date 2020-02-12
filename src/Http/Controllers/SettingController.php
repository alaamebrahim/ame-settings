<?php

namespace Alaame\Setting\Http\Controllers;

use Alaame\Setting\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;

class SettingController extends Controller
{
    private $settingService;

    /**
     * SettingController constructor.
     * @param SettingService $settingService
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->prepareSettings();

        $groups = $this->settingService->prepareSettingsGroups();

        $active = (request()->session()->has('setting_tab')) ?
            request()->session()->get('setting_tab') :
            old('setting_tab', key($settings));

        return view('ame-setting::index', compact('settings', 'groups', 'active'));
    }

    public function store(Request $request)
    {

        $setting = $this->settingService->createSetting($request);

        request()->flashOnly('setting_tab');

        if ($setting == null) {
            flash()->error(__('ame-setting::settings.key_already_exists', ['key' => '']));
        } else {
            flash()->success(__('ame-setting::settings.successfully_created'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->settingService->updateSettings($request);

        request()->flashOnly('setting_tab');

        flash()->success(__('ame-setting::settings.successfully_saved'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws \Exception
     */
    public function destroy($id)
    {
        $setting = $this->settingService->getSetting($id);

        $setting->delete();

        request()->session()->flash('setting_tab', $setting->group);

        flash()->success(__('ame-setting::settings.successfully_deleted'));

        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function move_up($id)
    {

        $setting = $this->settingService->getSetting($id);
        $swapOrder = $setting->getAttribute('order');
        $previousSetting = $this->settingService->getPreviousSetting($swapOrder, $setting->getAttribute('group'));

        if (isset($previousSetting->order)) {
            $setting->setAttribute('order', $previousSetting->getAttribute('order'));
            $setting->save();
            $previousSetting->setAttribute('order', $swapOrder);
            $previousSetting->save();

            flash()->success(__('ame-setting::settings.moved_order_up', ['name' => $setting->getAttribute('display_name')]));
        } else {
            flash()->error(__('ame-setting::settings.already_at_top'));
        }

        request()->session()->flash('setting_tab', $setting->getAttribute('group'));

        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function delete_value($id)
    {
        $setting = $this->settingService->getSetting($id);

        if (isset($setting->id)) {
            // If the type is an image... Then delete it
            if ($setting->type == 'image') {
                if (Storage::disk('public')->exists($setting->getAttribute('value'))) {
                    Storage::disk('public')->delete($setting->getAttribute('value'));
                }
            }
            $setting->setAttribute('value', '');
            $setting->save();
        }

        request()->session()->flash('setting_tab', $setting->getAttribute('group'));

        flash()->success(__('ame-setting::settings.successfully_removed', ['name' => $setting->getAttribute('display_name')]));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function move_down($id)
    {

        $setting = $this->settingService->getSetting($id);

        $swapOrder = $setting->getAttribute('order');

        $previousSetting = $this->settingService->getNextSetting($swapOrder, $setting->group);

        if (isset($previousSetting->order)) {
            $setting->setAttribute('order', $previousSetting->getAttribute('order'));
            $setting->save();
            $previousSetting->setAttribute('order', $swapOrder);
            $previousSetting->save();

            flash()->success(__('ame-setting::settings.moved_order_down', ['name' => $setting->getAttribute('display_name')]));
        } else {
            flash()->error(__('ame-setting::settings.already_at_bottom'));

        }

        request()->session()->flash('setting_tab', $setting->getAttribute('group'));

        return back();
    }
}
