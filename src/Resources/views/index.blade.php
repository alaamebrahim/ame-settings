@extends(config('ame-setting.layout'))

@section('pageTitle', __('ame-setting::page_title'))
@section('content')
    @if (flash()->message)
        <div id="alert" class="{{ flash()->class }} p-2 mb-2 rounded flex content-center items-center justify-between" role="alert">
            {{ flash()->message }}
            <i class="fas fa-window-close float-left cursor-pointer" onclick="document.getElementById('alert').remove()"></i>
        </div>
    @endif

    @role('admin')
        @include('ame-setting::update')
    @endrole
    @role('developer')
        @include('ame-setting::create')
    @endrole
    <form action="#" id="delete_form" method="POST" class="hidden">
        {{ method_field("DELETE") }}
        {{ csrf_field() }}
        <input type="submit" class="btn btn-danger pull-right delete-confirm"
               value="{{ __('admin::settings.delete_confirm') }}">
    </form>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>
    <style>
        #options_editor {
            min-height: 150px;
        }

        .delete-setting {
            cursor: pointer;
        }

        .panel-actions {
            cursor: pointer;
        }

        .panel-actions {
            color: #e94542;
        }

        .settings .panel-actions {
            right: 0px;
        }

        .panel hr {
            margin-bottom: 10px;
        }

        .panel {
            padding-bottom: 15px;
        }

        .panel-title code {
            border-radius: 30px;
            padding: 5px 10px;
            font-size: 11px;
            border: 0;
            position: relative;
            top: -2px;
        }

        .modal-open .settings {
            z-index: 9 !important;
            width: 100% !important;
        }

        .new-setting {
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .new-setting .panel-title {
            margin: 0 auto;
            display: inline-block;
            color: #999fac;
            font-weight: lighter;
            font-size: 13px;
            background: #fff;
            width: auto;
            height: auto;
            position: relative;
            padding-right: 15px;
        }

        .settings .panel-title {
            padding-left: 0px;
            padding-right: 0px;
        }

        .new-setting hr {
            margin-bottom: 0;
            position: absolute;
            top: 7px;
            width: 96%;
            margin-left: 2%;
        }

        .new-setting .panel-title i {
            position: relative;
            top: 2px;
        }

        .new-settings-options {
            display: none;
            padding-bottom: 10px;
        }

        .new-settings-options label {
            margin-top: 13px;
        }

        .new-settings-options .alert {
            margin-bottom: 0;
        }

        #toggle_options {
            clear: both;
            float: right;
            font-size: 12px;
            position: relative;
            margin-top: 15px;
            margin-right: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            z-index: 9;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .new-setting-btn {
            margin-right: 15px;
            position: relative;
            margin-bottom: 0;
            top: 5px;
        }

        .new-setting-btn i {
            position: relative;
            top: 2px;
        }

        textarea {
            min-height: 120px;
        }

        textarea.hidden {
            display: none;
        }

        .voyager .settings {
            background: none;
            border-bottom: 0px;
        }

        .voyager .settings .nav-tabs .active a {
            border: 0px;
        }

        .voyager .settings input[type=file] {
            width: 100%;
        }

        .settings {
            margin-left: 10px;
        }

        .settings {
            height: 32px;
            padding: 2px;
        }

        .voyager .settings .nav-tabs > li {
            margin-bottom: -1px !important;
        }

        .voyager .settings .nav-tabs a {
            text-align: center;
            background: #f8f8f8;
            border: 1px solid #f1f1f1;
            position: relative;
            top: -1px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .voyager .settings .nav-tabs a i {
            display: block;
            font-size: 22px;
        }

        .tab-content {
            background: #ffffff;
            border: 1px solid transparent;
        }

        .tab-content > div {
            padding: 10px;
        }

        .settings .no-padding-left-right {
            padding-left: 0px;
            padding-right: 0px;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            background: #fff !important;
            color: #62a8ea !important;
            border-bottom: 1px solid #fff !important;
            top: -1px !important;
        }

        .nav-tabs > li a {
            transition: all 0.3s ease;
        }


        .nav-tabs > li.active > a:focus {
            top: 0px !important;
        }

        .voyager .settings .nav-tabs > li > a:hover {
            background-color: #fff !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.5/ace.js"></script>
    <script>
        $('document').ready(function () {

            $('.delete-setting').click(function () {
                var display = $(this).data('display-name') + '/' + $(this).data('display-key');

                $('#delete_setting_title').text(display);

                $('#delete_form')[0].action = '{{ route('admin.setting.delete', [ 'id' => '__id' ]) }}'.replace('__id', $(this).data('id'));

                const ok = window.confirm('هل أنت متأكد من الحذف؟');
                if(ok === true) {
                    $('#delete_form')[0].submit();
                }
            });

            $('.delete_value').click(function (e) {
                e.preventDefault();
                $(this).closest('form').attr('action', $(this).attr('href'));
                $(this).closest('form').submit();
            });
        });
    </script>
    <script type="text/javascript">
        $(".group_select").not('.group_select_new').select2({
            tags: true,
            width: 'resolve'
        });
        $(".group_select_new").select2({
            tags: true,
            width: 'resolve',
            placeholder: '{{ __("ame-setting::settings.select_group") }}'
        });
        $(".group_select_new").val('').trigger('change');
    </script>

    <script>
        const options_editor = ace.edit('options_editor');
        options_editor.getSession().setMode("ace/mode/json");

        const options_textarea = document.getElementById('options_textarea');
        options_editor.getSession().on('change', function () {
            options_textarea.value = options_editor.getValue();
        });
    </script>
    <script>
        $( function() {
            $( "#tabs" ).tabs({
                classes: {
                    "ui-tabs-active": "bg-orange-900"
                }
            });
        } );
    </script>
    <script>
        document.getElementById('toggle_options').addEventListener('click', function () {
            document.getElementById('options_container').classList.toggle('new-settings-options')
        });
    </script>

    @stack('more-js')
@stop
