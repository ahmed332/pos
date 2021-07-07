<div id="hr_delete_activites_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="hr_delete_activites_modal" aria-hidden="true" >
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">{{trans('admin.Delete_Record')}}</h4>
                </div>
                <div class="modal-body">
                    <h4>{{trans('admin.You_Want_You_Sure_Delete_This_Record')}}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('admin.close')}}</button>
                    <button type="submit" class="btn btn-danger">{{trans('admin.delete')}}</button>
                </div>
            </form>

        </div>
    </div>
</div>
