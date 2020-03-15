@hasanyrole('admin|developer')
<div class="w-full bg-white p-1 rounded shadow mb-3">
    <div class="w-full flex flex-col">
        <form action="{{ route('admin.setting.update') }}" method="POST"
              enctype="multipart/form-data">
            {{ method_field("PUT") }}
            {{ csrf_field() }}
            <input type="hidden" name="setting_tab" class="setting_tab" value="{{ $active }}"/>
            <div class="w-full flex-col" id="tabs">
                <ul class="flex mt-5">
                    @foreach($settings as $group => $setting)
                        @hasrole('admin')
                        <li class="mr-1">
                            <a class="rounded bg-orange-500 text-white hover:bg-orange-800 transition duration-500 px-4 py-2"
                               href="#tabs-{{ Str::slug($group) }}">
                                {{ $group }}
                            </a>
                        </li>
                        @endhasrole
                    @endforeach
                </ul>
                @foreach($settings as $group => $group_settings)
                    <div id="tabs-{{ Str::slug($group) }}"
                         class="tab-pane w-full">
                        @foreach($group_settings as $setting)

                            <div class="p-3 mb-3 rounded px-3 hover:bg-gray-100 transition duration-500 ">
                                <div class="w-full flex flex-row ">
                                    <h5 class="text-xl uppercase">
                                        {{ __($setting->display_name) }}
                                    </h5>
                                    <div class="flex-grow text-left float-left">
                                        <a href="{{ route('admin.setting.move_up', $setting->id) }}">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                        <a href="{{ route('admin.setting.move_down', $setting->id) }}">
                                            <i class="fa fa-arrow-down"></i>
                                        </a>
                                        @role('developer')
                                        <span class="cursor-pointer px-2 text-danger delete-setting"
                                              data-id="{{ $setting->id }}">
                                                        <i class="text-xl p-1 fas fa-trash text-red-500"></i>
                                                    </span>
                                        @endrole
                                    </div>
                                </div>

                                <div class="flex justify-center  w-full">
                                    <div class="@role('developer') w-10/12 @else w-full @endrole flex">
                                        @if ($setting->type == "text")
                                            <input type="text"
                                                   class="flex-grow bg-white p-1 border border-gray-200 w-full m-1"
                                                   name="{{ $setting->key }}"
                                                   value="{{ $setting->value }}">
                                        @elseif($setting->type == "text_area")
                                            <textarea class="bg-white p-1 border border-gray-200 w-full m-1"
                                                      name="{{ $setting->key }}">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "rich_text_box")
                                            <textarea
                                                    class="bg-white p-1 border border-gray-200 w-full m-1 richTextBox"
                                                    name="{{ $setting->key }}">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "code_editor")
                                            <?php $options = json_decode($setting->details); ?>
                                            <div id="{{ $setting->key }}"
                                                 data-theme="{{ @$options->theme }}"
                                                 data-language="{{ @$options->language }}"
                                                 class="ace_editor h-24 w-full min_height_400"
                                                 name="{{ $setting->key }}">{{ $setting->value ?? '' }}</div>
                                            <textarea name="{{ $setting->key }}"
                                                      id="{{ $setting->key }}_textarea"
                                                      class="hidden">{{ $setting->value ?? '' }}</textarea>
                                            @push('more-js')
                                                <script>
                                                    var editor_{{ str_replace('.', '', $setting->key) }} = ace.edit("{{ $setting->key }}");
                                                    editor_{{ str_replace('.', '', $setting->key) }}.session.setMode("ace/mode/javascript");
                                                </script>
                                            @endpush
                                        @elseif($setting->type == "image" || $setting->type == "file")
                                            <div class="flex flex-row justify-center items-center w-full">
                                                @if(isset( $setting->value ) && !empty( $setting->value ) && Storage::disk('public')->exists($setting->value))
                                                    <div class="flex justify-center items-center m-1">
                                                        <a href="{{ route('admin.setting.delete_value', $setting->id) }}"
                                                           class="fa fa-trash text-red-500 mx-1"></a>
                                                        <img src="{{ Storage::disk('public')->url($setting->value) }}"
                                                             class="h-12 w-24 border border-gray-200">
                                                    </div>
                                                @elseif($setting->type == "file" && isset( $setting->value ))
                                                    <div class="fileType">{{ $setting->value }}</div>
                                                @endif
                                                <div class="w-full mx-2">
                                                    <input type="file"
                                                           class="border border-gray-200 p-0 w-full bg-gray-100 hover:bg-white justify-center items-center"
                                                           name="{{ $setting->key }}">
                                                </div>
                                            </div>
                                        @elseif($setting->type == "select_dropdown")
                                            <?php $options = json_decode($setting->details); ?>
                                            <?php $selected_value = (isset($setting->value) && !empty($setting->value)) ? $setting->value : NULL; ?>
                                            <select class="bg-white p-1 border border-gray-200 w-full m-1"
                                                    name="{{ $setting->key }}">
                                                <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                                @if(isset($options->options))
                                                    @foreach($options->options as $index => $option)
                                                        <option
                                                                value="{{ $index }}" @if($default == $index && $selected_value === NULL){{ 'selected="selected"' }}@endif @if($selected_value == $index){{ 'selected="selected"' }}@endif>{{ $option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        @elseif($setting->type == "radio_btn")
                                            <?php $options = json_decode($setting->details); ?>
                                            <?php $selected_value = (isset($setting->value) && !empty($setting->value)) ? $setting->value : NULL; ?>
                                            <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                            <ul class="radio">
                                                @if(isset($options->options))
                                                    @foreach($options->options as $index => $option)
                                                        <li>
                                                            <input type="radio"
                                                                   id="option-{{ $index }}"
                                                                   name="{{ $setting->key }}"
                                                                   value="{{ $index }}" @if($default == $index && $selected_value === NULL){{ 'checked' }}@endif @if($selected_value == $index){{ 'checked' }}@endif>
                                                            <label
                                                                    for="option-{{ $index }}">{{ $option }}</label>
                                                            <div class="check"></div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @elseif($setting->type == "checkbox")
                                            <?php $options = json_decode($setting->details); ?>
                                            <?php $checked = (isset($setting->value) && $setting->value == 1) ? true : false; ?>
                                            @if (isset($options->on) && isset($options->off))
                                                <input type="checkbox"
                                                       name="{{ $setting->key }}"
                                                       class="toggleswitch"
                                                       @if($checked) checked
                                                       @endif data-on="{{ $options->on }}"
                                                       data-off="{{ $options->off }}">
                                            @else
                                                <input type="checkbox"
                                                       name="{{ $setting->key }}"
                                                       @if($checked) checked
                                                       @endif class="toggleswitch">
                                            @endif
                                        @endif
                                    </div>
                                    @role('developer')
                                    <div class="w-2/12 justify-center items-center flex">
                                        <select class="bg-white p-1 border border-gray-200 w-full m-1"
                                                name="{{ $setting->key }}_group">
                                            @foreach($groups as $group)
                                                <option
                                                        value="{{ $group }}" {!! $setting->group == $group ? 'selected' : '' !!}>{{ $group }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endrole
                                </div>
                            </div>

                        @endforeach
                    </div>
                @endforeach

                <div class="w-full flex flex-col">
                    <div class=" text-left">
                        <button type="submit"
                                class="bg-orange-500 text-white hover:bg-orange-800 transition duration-500 p-1 rounded mb-1">
                            <i class="fa fa-save text-3xl p-1 mx-1"></i>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endhasanyrole
