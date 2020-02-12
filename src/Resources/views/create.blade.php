<div class="w-full flex">
    <div class="w-full">
        <div class="bg-white p-2 rounded shadow-lg">
            <div class="w-full d-block text-right">
                <h3 class="my-2 text-orange-600 text-2xl float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('ame-setting::settings.new') }}
                </h3>
            </div>

            <div class="w-full flex flex-row">
                <form action="{{ route('admin.setting.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting_tab" class="setting_tab"
                           value="{{ $active }}"/>
                    <div class="flex flex-col">
                        <div class="w-full flex justify-center items-end">
                            <div class="mx-1 flex-1">
                                <label for="display_name">{{ __('ame-setting::settings.name') }}</label>
                                <input type="text" class="bg-white p-1 border border-gray-200 m-1" name="display_name"
                                       placeholder="{{ __('ame-setting::settings.help_name') }}"
                                       required="required">
                            </div>
                            <div class="mx-1 flex-1">
                                <label for="key">{{ __('ame-setting::settings.key') }}</label>
                                <input type="text" class="bg-white p-1 border border-gray-200 w-full m-1" name="key"
                                       placeholder="{{ __('ame-setting::settings.help_key') }}"
                                       required="required">
                            </div>
                            <div class="mx-1 flex-1">
                                <label for="type">{{ __('ame-setting::settings.type') }}</label>
                                <select name="type" class="bg-white p-1 border border-gray-200 w-full m-1" required="required">
                                    <option value="">{{ __('ame-setting::settings.choose_type') }}</option>
                                    <option value="text">{{ __('ame-setting::settings.form.type_textbox') }}</option>
                                    <option
                                            value="text_area">{{ __('ame-setting::settings.form.type_textarea') }}</option>
                                    <option
                                            value="rich_text_box">{{ __('ame-setting::settings.form.type_richtextbox') }}</option>
                                    <option
                                            value="code_editor">{{ __('ame-setting::settings.form.type_codeeditor') }}</option>
                                    <option
                                            value="checkbox">{{ __('ame-setting::settings.form.type_checkbox') }}</option>
                                    <option
                                            value="radio_btn">{{ __('ame-setting::settings.form.type_radiobutton') }}</option>
                                    <option
                                            value="select_dropdown">{{ __('ame-setting::settings.form.type_selectdropdown') }}</option>
                                    <option value="file">{{ __('ame-setting::settings.form.type_file') }}</option>
                                    <option value="image">{{ __('ame-setting::settings.form.type_image') }}</option>
                                </select>
                            </div>
                            <div class="mx-1 flex-1">
                                <label for="group">{{ __('ame-setting::settings.group') }}</label>
                                <select class="bg-white p-1 border border-gray-200 w-full m-1 group_select group_select_new" name="group">
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}">{{ $group }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mx-1 flex-shrink">
                                <button type="submit" class="bg-orange-500 text-white hover:bg-orange-800 transition duration-500 p-1 rounded mb-1">
                                    <i class="fa fa-plus-circle"></i> {{ __('ame-setting::settings.add_new') }}
                                </button>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="w-full">
                                <a id="toggle_options"><i
                                            class="fa fa-arrow-down"></i> {{ mb_strtoupper(__('ame-setting::settings.options')) }}
                                </a>
                                <div id="options_container" class="new-settings-options w-full flex flex-col">
                                    <label class="w-full" for="options">{{ __('ame-setting::settings.options') }}
                                        <small>{{ __('ame-setting::settings.help_option') }}</small>
                                    </label>
                                    <div id="options_editor" class="h-64 w-full min_height_200"
                                         data-language="json"></div>
                                    <textarea id="options_textarea" name="details"
                                              class="hidden"></textarea>
                                    <div id="valid_options" class="alert-success alert"
                                         style="display:none">{{ __('ame-setting::settings.json.valid') }}</div>
                                    <div id="invalid_options" class="alert-danger alert"
                                         style="display:none">{{ __('ame-setting::settings.json.invalid') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
