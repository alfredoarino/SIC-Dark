<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{asset('assets/js/libs/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"> </script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

{{-- Incluimos los scripyts del TOASTR--}}
@toastr_js

{{-- INICIO SWEET ALERT --}}
<script src="{{asset('plugins/sweetalerts/promise-polyfill.js')}}"></script>
<script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
<script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
{{-- FIN SWEET ALERT --}}

<script>
    $(document).ready(function() {
        App.init();
    });
</script>


