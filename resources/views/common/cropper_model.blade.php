<div class="modal fade" id="copperModal" onchange="getCropper()" tabindex="-1" role="dialog" ria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Please Ensure the Image First </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                        </div>
                        <div class="col-md-4">
                            <div class="preview" width = "100" height = "auto"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" name="baseImage0" value="ImageView0" class="btn btn-primary" id="crop">Save</button>
            </div>
        </div>
    </div>
</div>

<form id="proposal-form" method="POST" action="{{ route('users.proposal.update') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $proposal->id }}" />
    <input type="hidden" class="form-control" name="cover" value="" />
    <input type="hidden" class="form-control" name="logo" value="" />
</form>
