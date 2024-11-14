<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Create a new section</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="section-form" class="row" method="POST" action="{{ route('users.section.add') }}">
                    @csrf
                    <input type="hidden" id="section-id" name="section_id" value="" />
                    <input type="hidden" id="proposal-id" name="proposal_id" value="" />
                    {{-- Section Title --}}
                    <div class="col-md-6 mb-3">
                        <label>Section Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter your section title" name="title" value="{{ old('title') }}">
                    </div>
                    {{-- Section Sub Title --}}
                    <div class="col-md-6 mb-3">
                        <label>Section Sub Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter your section sub title" name="sub_title" value="{{ old('sub_title') }}">
                    </div>
                    {{-- Section Description --}}
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="description">Section Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="editor" cols="59" rows="5" class="form-control" autofocus>{{ old('description') }}</textarea>
                        </div>
                    </div>
                    {{-- Select status --}}
                    <div class="col-md-6 mb-3">
                        <label>Status <span style="color:red;">*</span></label>
                        <select class="form-control" name="status">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id="submitBtn" onclick="submitSection()">
                    Create
                </button>
            </div>
        </div>
    </div>
</div>
