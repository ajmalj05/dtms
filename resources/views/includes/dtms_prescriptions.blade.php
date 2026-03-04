<style>
    #append_prescription_data td {
        height: 45px;
    }
</style>
<div class="table-responsive">
    <table class="table table-stripped table-hover">
        <thead>
        <tr>
            <th width="10%">Tablet</th>
            <th>Medication</th>
            <th width="25%">Generic</th>
            <th>Dose</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="append_prescription_data">

        </tbody>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-label">Select Type</label>
                        <select class="tablettype_options"    id="tablettype_options">
                            <option value=''>All</option>
                            {{LoadCombo("tablet_type_master","id","tablet_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-label">Select Medicine</label>
                        <select class="js-data-example-ajax"></select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-group">
                        @php
                                $patientId = $patient_data->id;
                        @endphp
                        <br>
                        <button type="button" class="btn btn-sm btn-primary" id="old-medicines" onclick="viewOldData('{{ $patientId }}')">Previous medication</button>
                    </div>
                </div>
            </div>
        </div>




    </table>
</div>

{{-- AI Prescription Validation — Inline Button + Results --}}
<style>
    @keyframes rxBtnAnime { 0% { background-position:0% 50%; } 100% { background-position:300% 50%; } }
    @keyframes rxSpin2 { to { transform: rotate(360deg); } }
    .rx-ai-panel { border-radius: 10px; overflow: hidden; margin-top: 12px; transition: all 0.3s; }
</style>

<div class="text-center mt-3 mb-1">
    <button type="button" id="ai-rx-validate-btn" onclick="openPrescriptionValidation()"
        style="background:transparent; border:none; padding:9px 26px; border-radius:25px; cursor:pointer;
               display:inline-flex; align-items:center; gap:8px; font-family:'Poppins','Roboto',sans-serif;
               font-weight:600; font-size:14px; position:relative; z-index:1;
               transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 15px rgba(0,0,0,0.08);"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102,126,234,0.35)'"
        onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'"
    >
        <span style="position:absolute; inset:-2px; background:linear-gradient(45deg,#00f2fe,#4facfe,#f093fb,#acb6e5,#74ebd5,#00f2fe);
                     background-size:300%; border-radius:28px; z-index:-2; filter:blur(4px); opacity:0.85;
                     animation:rxBtnAnime 3s linear infinite;"></span>
        <span style="position:absolute; inset:0; background:#fff; border-radius:25px; z-index:-1;"></span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="flex-shrink:0;">
            <defs><linearGradient id="rxBtnGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#00f2fe"/><stop offset="100%" style="stop-color:#f093fb"/>
            </linearGradient></defs>
            <path fill="url(#rxBtnGrad)" d="M19 3H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-4-4 1.41-1.41L12 14.17l6.59-6.59L20 9l-8 8z"/>
        </svg>
        <span style="background:linear-gradient(92deg,#00f2fe 0%,#4facfe 50%,#f093fb 100%);
                     -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">Validate with AI</span>
    </button>
</div>

{{-- Inline Results Panel (hidden until validation runs) --}}
<div id="rx-ai-inline-panel" class="rx-ai-panel" style="display:none; background:#f8f9fc; border:1px solid #e4e8f0;">

    {{-- Loading --}}
    <div id="rx-inline-loading" class="text-center py-4" style="display:none;">
        <div style="width:46px; height:46px; margin:0 auto 10px; position:relative;">
            <div style="width:46px; height:46px; border:4px solid #e8ecf0; border-top-color:#667eea; border-radius:50%; animation:rxSpin2 0.9s linear infinite;"></div>
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); font-size:16px;">💊</div>
        </div>
        <p class="mb-0" style="font-size:13px; color:#666;">Checking doses, interactions &amp; contraindications…</p>
    </div>

    {{-- Error --}}
    <div id="rx-inline-error" style="display:none;" class="p-3">
        <div class="alert alert-danger mb-0 py-2" style="font-size:13px;">
            <i class="fa fa-exclamation-triangle mr-1"></i>
            <span id="rx-inline-error-msg">An error occurred. Please try again.</span>
        </div>
    </div>

    {{-- Results --}}
    <div id="rx-inline-results" style="display:none; padding:14px 16px;">

        {{-- Header row: badge + dismiss --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div id="rx-inline-badge"></div>
            <div>
                <button type="button" onclick="runPrescriptionValidation()" title="Re-validate"
                    style="background:none; border:1px solid #667eea; color:#667eea; border-radius:20px;
                           font-size:11px; padding:3px 10px; cursor:pointer; margin-right:6px;">
                    <i class="fa fa-refresh"></i> Re-check
                </button>
                <button type="button" onclick="closeRxPanel()" title="Close"
                    style="background:none; border:1px solid #aaa; color:#888; border-radius:20px;
                           font-size:11px; padding:3px 10px; cursor:pointer;">
                    &times; Close
                </button>
            </div>
        </div>

        {{-- Issues --}}
        <div id="rx-inline-issues-section">
            <div id="rx-inline-issues-list"></div>
        </div>

        {{-- Recommendation --}}
        <div id="rx-inline-rec-section" class="mt-2"
             style="background:#fff; border-left:4px solid #0f3460; border-radius:0 6px 6px 0; padding:10px 14px;">
            <div style="font-size:12px; font-weight:700; color:#0f3460; margin-bottom:4px;">
                <i class="fa fa-user-md mr-1"></i> AI Recommendation
            </div>
            <p id="rx-inline-rec-text" class="mb-0" style="font-size:13px; color:#444; line-height:1.5;"></p>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted" style="font-size:11px;"><em>* AI-generated. Verify with clinical judgment.</em></small>
        </div>
    </div>
</div>



<script>
    function  viewOldData(patientId) {
        var visitId = localStorage.getItem("dtms_visitId");
        $('#view-old-data-modal').modal();
        getAllOldMedicineData(patientId,visitId);
    }

    function getAllOldMedicineData(patientId, visitId)
    {
        table = $('#old_medicine_data').DataTable({
            // scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            "dom": 'lfrtip',
            "destroy" : true,
            'ajax': {
                url: "<?php echo url('/') ?>/get-all-old-medicine-data",
                type: 'POST',
                "data": function(d) {
                    d.visitId= visitId;
                    d.patientId= patientId;
                }
            },
            "columns": [
                {
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "tablet_type"
                },
                {
                    "data": "tablet_name"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "dose"
                },
                {
                    "data": "remark"
                },
            ]
        });
    }
</script>
<script>
    var count=1;
    function removeFiled(id, thiss){
     if(!id.includes('tr'))
     {
        $(`#${id}`).remove();
     } else {
        let ajaxval = {
                id: thiss,
            };
            swal({
                title: 'Are you sure?',
                text: "You won't be able to recover this data!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {


                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('/'); ?>/precription/delete",
                        data: ajaxval,
                        success: function(result) {

                            if (result.status == 1) {
                                swal("Done", result.message, "success");
                            $(`#${id}`).remove();
                            } else {
                                sweetAlert("Oops...", result.message, "error");
                            }
                        },
                    });
                }
            })
     }

    }
    var medicine = [];

    function add_duplicate(tablettype_Id="", medicine_Id="",remarks="",dose="",tablettype_name='',medicine_name='',id='',generic_name=""){


            count++;
            let newId;
            if(id!= ''){
                var html = "<tr id='tr_"+id+"'>";
                    newId=`tr_${id}`
            }
            else{
                var html = "<tr id='new_"+count+"'>";
                    newId=`new_${count}`
            }

            html += "<td><input type='text'  id='tablet_type_name_" + count + "' class='form-control' readonly><input type='hidden' name='tablet_type_id[]' id='tablet_type_id_" + count + "' class='form-control'></td>";
            html += "<td><input type='text'  id='medicine_options_name_" + count + "' class='form-control' readonly><input type='hidden' name='medicine_id[]' id='medicine_options_" + count + "' class='form-control'></td>";
            html += "<td>"+generic_name+"</td>";
            html += "<td><input type='text'  name='dose[]' id='dose_" + count + "'  class='form-control' placeholder='Dose'></td>";
            html += "<td><input type='text' name='remarks[]' id='remarks_" + count + "' class='form-control' placeholder='Remarks'></td>";
            html += "<td><i style='color:red' class='fa fa-trash' onclick=removeFiled('"+newId+"','"+id+"');></i></td>";
            html += "</tr>"
            $('#append_prescription_data').append(html);
            if (tablettype_Id) {
                $('#tablet_type_id_' + count).val(tablettype_Id);
                $('#tablet_type_name_' + count).val(tablettype_name);
            }
            if (medicine_Id) {
                $('#medicine_options_' + count).val(medicine_Id);
                $('#medicine_options_name_' + count).val(medicine_name);
            }
            if (remarks) {
                $('#remarks_' + count).val(remarks);
            }
            if (dose) {
                $('#dose_' + count).val(dose);
            }


        // $(".js-data-example-ajax").empty();



        // $(".js-data-example-ajax").val('').select2("refresh");
        // $("#tablettype_options").val('').selectpicker('refresh');


    }

    // function getMedicineLists(typeid,count,medicineid="",dose=""){

    //     var url='{{route('getMedicineLists')}}';
    //     _token="{{csrf_token()}}";

    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: {type_id:typeid,_token:_token},
    //         success: function(result) {

    //             $('#medicine_options_'+count).empty().append(result).trigger('change');

    //             if(medicineid){
    //                 $('#medicine_options_'+count).val(medicineid).trigger('change');
    //             }


    //         },
    //     });


    //     //
    // }

    function getmedicinedose(medid,count,dose=""){

        if(!medid){
            return true;
        }
        if(dose){
            return true;
        }
        var url='{{route('getMedicineDoseValue')}}';
        _token="{{csrf_token()}}";

        $.ajax({
            type: "POST",
            url: url,
            data: {medid:medid,_token:_token},
            success: function(result) {
                var data=JSON.parse(result);
                $('#dose_'+count).val(data?.dose);

            },
        });
    }

    // ---- AI Prescription Inline Validation ----
    function openPrescriptionValidation() {
        var rows = document.querySelectorAll('#append_prescription_data tr');
        var medicines = [];
        rows.forEach(function(row) {
            var nameInput = row.querySelector('input[id^="medicine_options_name_"]');
            var doseInput = row.querySelector('input[name="dose[]"]');
            var freqInput = row.querySelector('input[name="remarks[]"]');
            var typeInput = row.querySelector('input[id^="tablet_type_name_"]');
            var name = nameInput ? nameInput.value.trim() : '';
            if (!name) return;
            medicines.push({
                name:        name,
                dose:        doseInput ? doseInput.value.trim() : '',
                frequency:   freqInput ? freqInput.value.trim() : '',
                tablet_type: typeInput ? typeInput.value.trim() : ''
            });
        });
        if (medicines.length === 0) {
            alert('No medicines in the prescription. Please add at least one medicine before validating.');
            return;
        }
        window._rxValidationMedicines = medicines;
        runPrescriptionValidation();
    }

    function runPrescriptionValidation() {
        var patientId = $('#patient_id').val();
        var medicines = window._rxValidationMedicines || [];
        if (!patientId) {
            $('#rx-ai-inline-panel').show();
            $('#rx-inline-loading').hide();
            $('#rx-inline-results').hide();
            $('#rx-inline-error').show();
            $('#rx-inline-error-msg').text('Patient ID not found. Please reload the page.');
            return;
        }
        // Show panel with loading state
        $('#rx-ai-inline-panel').show();
        $('#rx-inline-loading').show();
        $('#rx-inline-results').hide();
        $('#rx-inline-error').hide();
        $('#rx-inline-issues-list').empty();
        $('#rx-inline-rec-text').text('');
        $('#rx-inline-badge').empty();
        // Smooth scroll to panel
        $('html, body').animate({ scrollTop: $('#rx-ai-inline-panel').offset().top - 60 }, 300);

        $.ajax({
            url: "{{ route('ai.validate-prescription') }}",
            type: 'POST',
            data: { patient_id: patientId, medicines: medicines, _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#rx-inline-loading').hide();
                if (response.status !== 'success') {
                    $('#rx-inline-error').show();
                    $('#rx-inline-error-msg').text(response.message || 'Validation failed.');
                    return;
                }
                var data = response.data;
                $('#rx-inline-results').show();

                // Overall badge
                if (data.overall_safe) {
                    $('#rx-inline-badge').html(
                        '<span style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#d4edda,#c3e6cb);'
                        +'border:2px solid #28a745;border-radius:20px;padding:5px 14px;font-size:13px;font-weight:700;color:#155724;">'
                        +'<span>✅</span> All Clear — Prescription Appears Safe</span>'
                    );
                    $('#rx-inline-issues-section').hide();
                } else {
                    var critCount = (data.issues||[]).filter(function(i){return i.severity==='CRITICAL';}).length;
                    var col  = critCount > 0 ? '#dc3545' : '#e08000';
                    var bg   = critCount > 0 ? 'linear-gradient(135deg,#f8d7da,#f5c6cb)' : 'linear-gradient(135deg,#fff3cd,#ffeeba)';
                    var icon = critCount > 0 ? '🔴' : '⚠️';
                    var lbl  = critCount > 0 ? 'Critical Issues Found' : 'Review Required';
                    $('#rx-inline-badge').html(
                        '<span style="display:inline-flex;align-items:center;gap:6px;background:'+bg+';'
                        +'border:2px solid '+col+';border-radius:20px;padding:5px 14px;font-size:13px;font-weight:700;color:'+col+'">'
                        +'<span>'+icon+'</span> '+lbl+' ('+( data.issues||[]).length+' issue(s))</span>'
                    );
                    $('#rx-inline-issues-section').show();
                    var html = '';
                    (data.issues||[]).forEach(function(issue) {
                        var sev  = issue.severity || 'INFO';
                        var type = issue.type || 'OTHER';
                        var meds = (issue.medicines_involved||[]).join(', ');
                        var expl = issue.explanation || '';
                        var sc   = sev==='CRITICAL'?'#dc3545':(sev==='WARNING'?'#e08000':'#0077aa');
                        var sbg  = sev==='CRITICAL'?'#fff5f5':(sev==='WARNING'?'#fffbf0':'#f0f8ff');
                        var si   = sev==='CRITICAL'?'🔴':(sev==='WARNING'?'⚠️':'ℹ️');
                        var tl   = {DOSE_EXCEEDED:'💊 Dose',DRUG_INTERACTION:'⚡ Interaction',CONTRAINDICATION:'🚫 Contraindication',OTHER:'📋 Other'}[type]||type;
                        html += '<div style="background:'+sbg+';border-left:3px solid '+sc+';border-radius:0 6px 6px 0;padding:9px 12px;margin-bottom:8px;">'
                            +'<div class="d-flex justify-content-between mb-1">'
                            +'<span style="font-size:12px;font-weight:700;color:'+sc+';">'+si+' '+sev+'</span>'
                            +'<span style="font-size:11px;background:rgba(0,0,0,0.06);padding:1px 7px;border-radius:20px;color:#555;">'+tl+'</span>'
                            +'</div>'
                            +'<div style="font-size:12px;color:#444;margin-bottom:2px;"><strong>Medicines:</strong> '+meds+'</div>'
                            +'<div style="font-size:12px;color:#333;line-height:1.5;">'+expl+'</div>'
                            +'</div>';
                    });
                    $('#rx-inline-issues-list').html(html);
                }
                $('#rx-inline-rec-text').text(data.recommendations || 'No specific recommendations.');
            },
            error: function(xhr) {
                $('#rx-inline-loading').hide();
                $('#rx-inline-error').show();
                var msg = 'Failed to connect to AI service.';
                try { var r = JSON.parse(xhr.responseText); if(r.message) msg = r.message; } catch(e){}
                $('#rx-inline-error-msg').text(msg + ' (HTTP '+xhr.status+')');
            }
        });
    }

    function closeRxPanel() {
        $('#rx-ai-inline-panel').slideUp(200);
    }

</script>



<style>


</style>
