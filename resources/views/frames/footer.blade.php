{{-- <div class="footer">
    <div class="copyright">
        <p>Copyright © JDC &amp; Developed by <a href="#" target="_blank">Netroxe IT Solutions</a> 2022</p>
    </div>
</div> --}}


<!--**********************************
    Footer end
***********************************-->

<!--**********************************
   Support ticket button start
***********************************-->

<!--**********************************
   Support ticket button end
***********************************-->


</div>
<!--**********************************
Main wrapper end
***********************************-->

<!--**********************************
Scripts
***********************************-->
<!-- Required vendors -->

<script src="{{asset('/vendor/global/global.min.js')}}"></script>
<script src="{{asset('/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('/js/custom.min.js')}}"></script>
<script src="{{asset('/js/deznav-init.js')}}"></script>
<script src="{{asset('/js/jquery.toaster.js')}}"></script>


<script src="{{asset('/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
<!-- Apex Chart -->
{{-- <script src="./vendor/apexchart/apexchart.js"></script> --}}
<script src="{{asset('/js/securityjsfns.js')}}"></script>
<script src="{{asset('/js/validation-format.js')}}"></script>

<!-- Dashboard 1 -->
{{-- <script src="./js/dashboard/dashboard-1.js"></script> --}}

<script>
$(".nav-control").trigger("click");

$.ajaxSetup({
   headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});


$(document).ajaxSend(function(){
    $('#loader').show();
});
$(document).ajaxComplete(function(){
    $('#loader').hide();
});
</script>



<script src="{{asset('/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>  -->
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>  -->
{{-- <script src="./js/plugins-init/datatables.init.js"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>




<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>



<script>
    $('#searchpatient_MDT tbody').on('click', 'tr', function() {
        var data = table.row($(this)).data();
        window.location.href = '{{url("dtmshome")}}/'+data.id;
    });
</script>

</body>

</html>
