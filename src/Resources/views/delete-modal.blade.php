<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ __('admin::generic.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-trash"></i> {!! __('admin::settings.delete_question', ['setting' => '<span id="delete_setting_title"></span>']) !!}
                </h4>
            </div>
            <div class="modal-footer">
                <form action="#" id="delete_form" method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="{{ __('admin::settings.delete_confirm') }}">
                </form>
                <button type="button" class="btn btn-default pull-right"
                        data-dismiss="modal">{{ __('admin::generic.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
