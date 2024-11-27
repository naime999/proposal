<div class="modal fade" id="tempModal" tabindex="-1" role="dialog" aria-labelledby="addModalExample" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalExample">Save as template</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3">
                <div class="mb-3">
                    @if ($addedCategories->count() > 0)
                        @php
                        $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                        @endphp
                        @foreach ($addedCategories as $item)
                        @php
                        $randomColor = $colors[array_rand($colors)];
                        @endphp
                        <span class="badge {{ $randomColor }} p-2 mb-1">{{ $item->category->title }}</span>
                        @endforeach
                    @else
                        <span class="badge bg-secondary p-2">This template is not added to any category.</span>
                    @endif
                </div>
                <form id="save-temp-form" class="row" method="POST" action="{{ route('users.proposal.save.template') }}">
                    @csrf
                    <input type="hidden" id="proposalId" name="proposal_id" value="{{ $proposal->id }}" />
                    {{-- Select Category --}}
                    <div class="col-md-12 mb-3">
                        <label>Category <span style="color:red;">*</span></label>
                        <select class="form-control form-control-user @error('category_id') is-invalid @enderror" name="category_id">
                            <option value="" selected disabled>Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" id=""onclick="event.preventDefault(); document.getElementById('save-temp-form').submit();">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
