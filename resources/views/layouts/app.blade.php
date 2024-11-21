<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.head')

<body id="page-top">
    <div class="loader-overlay">
        <div class="lv-squares lv-mid md"></div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @if (!request()->routeIs('users.proposal.show'))
        @include('common.sidebar')
        @endif
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @if (!request()->routeIs('users.proposal.show'))
                @include('common.header')
                @else
                @include('common.builder')
                @endif
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @if (!request()->routeIs('users.proposal.show'))
            @include('common.footer')
            @endif
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('common.logout-modal')

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/app.js')}}"></script>


    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/js/all.min.js" ></script>
    <script>
        let loader = new lv();
        loader.initLoaderAll();
        var load = $('.loader-overlay');
        $(document).ready(function() {
            load.hide();
        });
    </script>
    @yield('scripts')
</body>

</html>
