<nav class="container-fluid bg-white topbar mb-4 static-top shadow d-flex align-items-center justify-content-between">

    <!-- Sidebar Toggle (Topbar) -->
    <div class="d-flex align-items-center justify-content-between">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu"aria-expanded="false" aria-controls="sidebarMenu"><i
                class="fas fa-list fa-sm"></i></button>
        <div class="border rounded client-info ml-2 p-2 fs-6 fw-normal">
        </div>
    </div>

    <!-- buttons -->
    <div class="d-flex align-items-center align-items-right justify-content-between">
        @if ($proposal->status != 2)
            @can('proposal-create', 'proposal-edit')
                <div class="form-group m-0 mr-2">
                    <input type="file" name="cover" onchange="fileDetailsCrop(this)" data-sl="1"
                        data-size="900x1250" data-attr="detailsImageView" data-val="coverImage"
                        data-id="{{ $proposal->id }}" id="details_image" class="image pt-1 form-control d-none"
                        accept="image/*">
                    <label class="btn btn-sm btn-success rounded m-0" for="details_image"><i
                            class="fas fa-gear pr-2"></i>Cover<label>
                </div>
            @endcan
            @can('proposal-create', 'proposal-edit')
                <button class="btn btn-sm btn-success" type="submit" onclick="saveData(this)"
                    data-id="{{ $proposal->id }}"><i class="fas fa-save pr-2"></i>Save</button>
            @endcan
            @can('proposal-create', 'proposal-edit')
                <button class="btn btn-sm btn-success ml-2" type="submit" onclick="sendData(this)"
                    data-id="{{ $proposal->id }}"><i class="fas fa-solid fa-envelope pr-2"></i>Send</button>
            @endcan
        @endif
        <a class="btn btn-sm btn-success ml-2" href="{{ url()->previous() }}"><i
                class="fas fa-arrow-left pr-2"></i>back</a>
    </div>
</nav>
