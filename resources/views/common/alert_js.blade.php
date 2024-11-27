@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        });
    </script>
@endif
@if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        });
    </script>
@endif
@if(session('warning'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: "{{ session('warning') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1500
            });
        });
    </script>
@endif

