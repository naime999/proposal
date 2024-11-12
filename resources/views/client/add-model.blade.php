<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Create a claint</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="user-add-form" class="row" method="POST" action="{{ route('users.client.create') }}">
                    @csrf
                    <div class="col-md-6 mb-3">
                        <label>First Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your client first name" name="first_name" value="{{ old('first_name') }}">

                        @error('first_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Last Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('last_name') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your client last name" name="last_name" value="{{ old('last_name') }}">

                        @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('email') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your email address" name="email" value="{{ old('email') }}">

                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('mobile_number') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your client phone number" name="mobile_number" value="{{ old('mobile_number') }}">

                        @error('mobile_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Select status --}}
                    <div class="col-md-6 mb-3">
                        <label>Status <span style="color:red;">*</span></label>
                        <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success" onclick="event.preventDefault(); document.getElementById('user-add-form').submit();">
                    Create
                </button>
            </div>
        </div>
    </div>
</div>
