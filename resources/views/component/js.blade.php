<!-- Vendor JS Files -->
<script src={{ asset('vendor/apexcharts/apexcharts.min.js') }}></script>
<script src={{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}></script>
<script src={{ asset('vendor/chart.js/chart.umd.js') }}></script>
<script src={{ asset('vendor/echarts/echarts.min.js') }}></script>
<script src={{ asset('vendor/quill/quill.min.js') }}></script>
{{-- <script src={{ asset('vendor/simple-datatables/simple-datatables.js') }}></script> --}}
<script src={{ asset('vendor/tinymce/tinymce.min.js') }}></script>
<script src={{ asset('vendor/php-email-form/validate.js') }}></script>

{{-- Datatable --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>

<!-- Template Main JS File -->
<script src={{ asset('assets/js/main.js') }}></script>

{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Batasi panjang input nomor 
    function limitNumberLength(input, maxLength) {
            if (input.value.length > maxLength) {
                input.value = input.value.substring(0, maxLength);
            }
        }
</script>

<!-- Custom page scripts -->
@stack('scripts')
