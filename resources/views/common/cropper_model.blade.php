<div class="cropperModal fade" id="copperModal" onchange="getCropper()" tabindex="-1" role="dialog" ria-labelledby="modalLabel" aria-hidden="true">
    <div class="cropperModal-dialog cropperModal-lg" role="document">
        <div class="cropperModal-content">
            <div class="cropperModal-header">
                <h5 class="cropperModal-title" id="modalLabel">Please Ensure the Image First </h5>
                <button type="button" class="btn-close" data-bs-dismiss="cropperModal" aria-label="Close"></button>
            </div>
            <div class="cropperModal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cropperModal-footer">
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="cropperModal" aria-label="Close">Cancel</button>
                <button type="button" name="baseImage0" value="ImageView0" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
