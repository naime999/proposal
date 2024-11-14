<div class="modal fade addProposal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Create a new propasal</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="user-add-form" class="row" method="POST" action="{{ route('users.proposal.create') }}">
                    @csrf
                    {{-- Select client --}}
                    <div class="col-md-12 mb-3">
                        <label>Client <span style="color:red;">*</span></label>
                        <select class="form-control form-control-user @error('client_id') is-invalid @enderror" name="client_id">
                            <option value="" selected disabled>Select a client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name . ' ' . $client->last_name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- First Name --}}
                    <div class="col-md-12 mb-3">
                        <label>Proposal Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('title') is-invalid @enderror" id="exampleFirstName" placeholder="Enter your proposal title" name="title" value="{{ old('title') }}">

                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Select status --}}
                    {{-- <div class="col-md-12 mb-3">
                        <label>Status <span style="color:red;">*</span></label>
                        <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                            <option value="0" selected>Draft</option>
                            <option value="1">Active</option>
                            <option value="3">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-success"
                    onclick="event.preventDefault(); document.getElementById('user-add-form').submit();">
                    Create
                </button>
            </div>
        </div>
    </div>
</div>
