<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Create a Signature</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3">
                <form id="signature-form" class="row" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="userType" name="user_type" value="1" />
                    <input type="hidden" id="proposalId" name="proposal_id" value="" />
                    <div class="btn-group p-0 mb-3" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="sig_type" value="1" data-show="#typing" id="btnradio1" autocomplete="off" checked="checked">
                        <label class="btn btn-outline-primary" for="btnradio1">Type Signature</label>

                        <input type="radio" class="btn-check" name="sig_type" value="2" data-show="#uploading" id="btnradio2" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btnradio2">Upload Signature</label>

                        <input type="radio" class="btn-check" name="sig_type" value="3" data-show="#signature-pad" id="btnradio3" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btnradio3">Draw Signature</label>
                    </div>

                    <div class="col-md-12 border rounded justify-content-center align-items-center sig-view" id="typing" style="height: 80px; background-color: #ececec;">
                        @if ($proposal->adminSignature)
                            @if ($proposal->adminSignature->title != null)
                                <span class="defult-signature" contenteditable="true">
                                    {{ $proposal->adminSignature->title }}
                                </span>
                            @else
                                <span class="defult-signature" contenteditable="true">
                                    {{ $proposal->creator->first_name.' '.$proposal->creator->last_name }}
                                </span>
                            @endif
                        @else
                            <span class="defult-signature" contenteditable="true">
                                {{ $proposal->creator->first_name.' '.$proposal->creator->last_name }}
                            </span>
                        @endif
                    </div>

                    <div class="col-md-12 border rounded justify-content-center align-items-center sig-view" id="uploading" style="height: 80px; background-color: #ececec;">
                        <input type="hidden" class="form-control" id="baseImage" name="upload_image" value="" />
                        <div class="input-group justify-content-center align-items-center h-100 imageUpload">
                            <input type="file" class="form-control d-none" id="imageUpload" accept="image/*">
                            <label class="btn btn-primary rounded m-0" for="imageUpload">Choose Signature</label>
                        </div>
                    </div>

                    <div class="col-md-12 border rounded sig-view signature-pad" id="signature-pad" style="height: 200px;">
                        <div class="signature-pad--body">
                            <canvas width="258" height="80"></canvas>
                        </div>
                        <div class="signature-pad--footer">
                            <div class="description">Sign above</div>

                            <div class="signature-pad--actions">
                                <div>
                                    <button type="button" class="button clear" data-action="clear">Clear</button>
                                    <button type="button" class="button" data-action="change-color">Change color</button>
                                    <button type="button" class="button" data-action="undo">Undo</button>
                                </div>
                                {{-- <div>
                                    <button type="button" class="button save" data-action="save-png">Save as PNG</button>
                                    <button type="button" class="button save" data-action="save-jpg">Save as JPG</button>
                                    <button type="button" class="button save" data-action="save-svg">Save as SVG</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id="" onclick="saveSignature()">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
