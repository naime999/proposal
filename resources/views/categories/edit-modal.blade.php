<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Edit this category</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="category-edit-form" class="row" method="POST" action="{{ route('users.category.update') }}">
                    @csrf
                    {{-- First Name --}}
                    <input type="hidden" name="id" name="id" value="">
                    <div class="col-md-12 mb-3">
                        <label>Category Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('title') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your category title" name="title" value="{{ old('title') }}">

                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success"
                    onclick="event.preventDefault(); document.getElementById('category-edit-form').submit();">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>
