<nav class="container-fluid bg-white topbar mb-4 static-top shadow d-flex align-items-center justify-content-between">

    <!-- Sidebar Toggle (Topbar) -->
    <div class="d-flex align-items-center justify-content-between">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"aria-expanded="false" aria-controls="sidebarMenu"><i class="fas fa-list fa-sm"></i></button>
        <div class="border rounded client-info ml-2 p-2 fs-6 fw-normal">
        </div>
    </div>

    <!-- Topbar Search -->
    {{-- <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-1 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form> --}}
    <!-- Save button -->
    <div class="d-flex align-items-right justify-content-between">
        <button class="btn btn-sm btn-success" type="submit" onclick="saveData(this)" data-id="{{ $proposal->id }}"><i class="fas fa-save pr-2"></i>Save</button>
        <a class="btn btn-sm btn-success ml-2" href="{{ url()->previous() }}"><i class="fas fa-arrow-left pr-2"></i>back</a>
    </div>
</nav>
