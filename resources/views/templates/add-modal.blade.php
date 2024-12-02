<div class="modal fade addProposal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Create a propasal like this</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="proposal-duplicate-form" class="row" method="POST" action="{{ route('users.proposal.duplicate') }}">
                    @csrf
                    <input type="hidden" name="id" value="" />
                    {{-- Select client --}}
                    <div class="col-md-12 mb-3">
                        <label>Client <span style="color:red;">*</span></label>
                        <select class="form-control form-control-user @error('client_id') is-invalid @enderror" name="client_id">
                            <option value="" selected disabled>Select a client</option>
                            @foreach ($clients as $client)
                                @if ($client->userInfo->email_verified_at)
                                    <option value="{{ $client->userInfo->id }}">{{ $client->userInfo->first_name . ' ' . $client->userInfo->last_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('client_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Proposal Title --}}
                    <div class="col-md-12 mb-3">
                        <label>Proposal Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-user @error('title') is-invalid @enderror" id="exampleFirstName" placeholder="Please enter a new proposal title" name="title" value="{{ old('title') }}">

                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" onclick="document.getElementById('proposal-duplicate-form').submit();">
                    Create
                </button>
            </div>
        </div>
    </div>
</div>
