<div id="EditProfileModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar</h5>
                <button type="button" aria-label="Close" class="close outline-none" data-dismiss="modal">×</button>
            </div>
            <form method="POST" id="editProfileForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="editProfileValidationErrorsBox"></div>
                    <input type="hidden" name="user_id" id="pfUserId">
                    <input type="hidden" name="is_active" value="1">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Nombre:</label><span class="required">*</span>
                            <input type="text" name="name" id="pfName" class="form-control" disabled required autofocus tabindex="1">
                        </div>
                        <div class="form-group col-sm-6 d-flex">
                            <div class="col-sm-4 col-md-6 pl-0 form-group">
                                <label>Imagen:</label>
                                <br>
                               
                            </div>
                            <div class="col-sm-3 preview-image-video-container float-right mt-1">
                                <div id="edit_preview_photo"
                                    class="avatar user-img user-profile-img profilePicture d-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr(\Auth::user()->name, 0, 2)) }}

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Correo:</label><span class="required">*</span>
                            <input type="text" name="email" id="pfEmail" class="form-control" disabled required tabindex="3">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-light ml-1 edit-cancel-margin margin-left-5"
                                data-dismiss="modal">Cerrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .avatar{
        color:rgb(255, 255, 255);
        background: #c6d219;
        font-weight: 800;
        width:80px;
        height: 80px;
        font-size: 30px;
    }
</style>