{{-- AI Prescription Validation Modal --}}
<div class="modal fade" id="prescriptionValidationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">

            {{-- Header --}}
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); border-bottom: none; padding: 20px 25px;">
                <div class="d-flex align-items-center">
                    <div style="width:40px; height:40px; background: rgba(255,255,255,0.15); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin-right:12px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <defs>
                                <linearGradient id="rxGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#00f2fe"/>
                                    <stop offset="100%" style="stop-color:#f093fb"/>
                                </linearGradient>
                            </defs>
                            <path fill="url(#rxGrad)" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-4-4 1.41-1.41L12 14.17l6.59-6.59L20 9l-8 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; letter-spacing: 0.3px;">AI Prescription Safety Check</h5>
                        <small style="opacity: 0.7; font-size: 12px;">Powered by Gemini · Dose · Interactions · Contraindications</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 22px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="background: #f8f9fc; padding: 25px;">

                {{-- Loading State --}}
                <div id="rxValidationLoading" class="text-center p-5">
                    <div style="width: 60px; height: 60px; margin: 0 auto 16px; position: relative;">
                        <div style="width: 60px; height: 60px; border: 4px solid #e8ecf0; border-top-color: #667eea; border-radius: 50%; animation: rxSpin 0.9s linear infinite;"></div>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-size: 20px;">💊</div>
                    </div>
                    <h6 style="color: #333; font-weight: 600; margin-bottom: 6px;">Analysing Prescription…</h6>
                    <p class="text-muted mb-0" style="font-size: 13px;">Checking doses, interactions &amp; contraindications against patient profile.</p>
                    <style>@keyframes rxSpin { to { transform: rotate(360deg); } }</style>
                </div>

                {{-- Error State --}}
                <div id="rxValidationError" style="display:none;" class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle mr-2"></i>
                    <span id="rxValidationErrorMsg">An error occurred. Please try again.</span>
                </div>

                {{-- Results --}}
                <div id="rxValidationResults" style="display:none;">

                    {{-- Overall Badge --}}
                    <div class="text-center mb-4">
                        <div id="rxOverallBadge"></div>
                    </div>

                    {{-- Issues List --}}
                    <div id="rxIssuesSection">
                        <h6 style="font-weight: 700; color: #2c3e50; margin-bottom: 12px;">
                            <i class="fa fa-flag-o mr-1 text-danger"></i> Issues Detected
                        </h6>
                        <div id="rxIssuesList"></div>
                    </div>

                    {{-- Recommendations --}}
                    <div id="rxRecommendationsSection" class="mt-4" style="background: #fff; border-left: 4px solid #0f3460; border-radius: 0 8px 8px 0; padding: 14px 16px;">
                        <h6 style="font-weight: 700; color: #0f3460; margin-bottom: 6px;">
                            <i class="fa fa-user-md mr-1"></i> AI Recommendation
                        </h6>
                        <p id="rxRecommendationsText" class="mb-0" style="font-size: 14px; color: #444; line-height: 1.6;"></p>
                    </div>

                    {{-- Disclaimer --}}
                    <div class="mt-3 text-center">
                        <small class="text-muted"><em>* AI-generated. Always verify with clinical judgment before prescribing.</em></small>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="background: #f0f2f5; border-top: 1px solid #e0e4ea; padding: 12px 20px;">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm text-white" id="rxRevalidateBtn" onclick="runPrescriptionValidation()" style="background: linear-gradient(135deg,#667eea,#764ba2); border:none; padding: 6px 18px; border-radius: 6px;">
                    <i class="fa fa-refresh mr-1"></i> Re-validate
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Open the Prescription Validation Modal and trigger validation
    function openPrescriptionValidation() {
        var rows  = document.querySelectorAll('#append_prescription_data tr');
        var medicines = [];

        rows.forEach(function(row) {
            var nameInput = row.querySelector('input[id^="medicine_options_name_"]');
            var doseInput = row.querySelector('input[name="dose[]"]');
            var remarksInput = row.querySelector('input[name="remarks[]"]');
            var typeInput    = row.querySelector('input[id^="tablet_type_name_"]');

            var name = nameInput ? nameInput.value.trim() : '';
            if (!name) return; // skip empty rows

            medicines.push({
                name:        name,
                dose:        doseInput     ? doseInput.value.trim()     : '',
                frequency:   remarksInput  ? remarksInput.value.trim()  : '',
                tablet_type: typeInput     ? typeInput.value.trim()     : ''
            });
        });

        if (medicines.length === 0) {
            alert('No medicines found in the prescription table. Please add at least one medicine before validating.');
            return;
        }

        // Store globally for re-validation
        window._rxValidationMedicines = medicines;

        $('#prescriptionValidationModal').modal('show');
        runPrescriptionValidation();
    }

    function runPrescriptionValidation() {
        var patientId = $('#patient_id').val();
        var medicines = window._rxValidationMedicines || [];

        if (!patientId) {
            $('#rxValidationLoading').hide();
            $('#rxValidationError').show();
            $('#rxValidationErrorMsg').text('Patient ID not found. Please reload the page.');
            return;
        }

        // Reset UI
        $('#rxValidationLoading').show();
        $('#rxValidationResults').hide();
        $('#rxValidationError').hide();
        $('#rxIssuesList').empty();
        $('#rxRecommendationsText').text('');
        $('#rxOverallBadge').empty();

        $.ajax({
            url: "{{ route('ai.validate-prescription') }}",
            type: 'POST',
            data: {
                patient_id: patientId,
                medicines:  medicines,
                _token:     "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#rxValidationLoading').hide();

                if (response.status !== 'success') {
                    $('#rxValidationError').show();
                    $('#rxValidationErrorMsg').text(response.message || 'Validation failed.');
                    return;
                }

                var data = response.data;
                $('#rxValidationResults').show();

                // --- Overall badge ---
                if (data.overall_safe) {
                    $('#rxOverallBadge').html(
                        '<div style="display:inline-flex; align-items:center; background: linear-gradient(135deg,#d4edda,#c3e6cb); border: 2px solid #28a745; border-radius: 30px; padding: 10px 24px; gap: 10px;">' +
                        '<span style="font-size:28px;">✅</span>' +
                        '<div><div style="font-size:17px; font-weight:700; color:#155724;">All Clear — Prescription Appears Safe</div>' +
                        '<div style="font-size:12px; color:#555;">No significant issues detected for this patient profile.</div></div>' +
                        '</div>'
                    );
                    $('#rxIssuesSection').hide();
                } else {
                    var criticalCount = (data.issues || []).filter(function(i){ return i.severity === 'CRITICAL'; }).length;
                    var badgeColor    = criticalCount > 0 ? 'linear-gradient(135deg,#f8d7da,#f5c6cb)' : 'linear-gradient(135deg,#fff3cd,#ffeeba)';
                    var badgeBorder   = criticalCount > 0 ? '#dc3545' : '#ffc107';
                    var badgeTextClr  = criticalCount > 0 ? '#721c24' : '#856404';
                    var icon          = criticalCount > 0 ? '🔴' : '⚠️';
                    var title         = criticalCount > 0 ? 'Critical Issues Found' : 'Warnings — Review Required';

                    $('#rxOverallBadge').html(
                        '<div style="display:inline-flex; align-items:center; background:' + badgeColor + '; border: 2px solid ' + badgeBorder + '; border-radius: 30px; padding: 10px 24px; gap: 10px;">' +
                        '<span style="font-size:28px;">' + icon + '</span>' +
                        '<div><div style="font-size:17px; font-weight:700; color:' + badgeTextClr + ';">' + title + '</div>' +
                        '<div style="font-size:12px; color:#555;">' + (data.issues || []).length + ' issue(s) identified below.</div></div>' +
                        '</div>'
                    );
                    $('#rxIssuesSection').show();

                    // --- Issues ---
                    var issuesHtml = '';
                    (data.issues || []).forEach(function(issue) {
                        var sev      = issue.severity || 'INFO';
                        var type     = issue.type || 'OTHER';
                        var meds     = (issue.medicines_involved || []).join(', ');
                        var expl     = issue.explanation || '';

                        var sevColor = sev === 'CRITICAL' ? '#dc3545' : sev === 'WARNING' ? '#e08000' : '#0077aa';
                        var sevBg    = sev === 'CRITICAL' ? '#fff0f0'  : sev === 'WARNING' ? '#fffbf0'  : '#f0f8ff';
                        var sevIcon  = sev === 'CRITICAL' ? '🔴' : sev === 'WARNING' ? '⚠️' : 'ℹ️';

                        var typeLabel = {
                            'DOSE_EXCEEDED':    '💊 Dose Exceeded',
                            'DRUG_INTERACTION': '⚡ Drug Interaction',
                            'CONTRAINDICATION': '🚫 Contraindication',
                            'OTHER':            '📋 Other'
                        }[type] || type;

                        issuesHtml +=
                            '<div style="background:' + sevBg + '; border-left: 4px solid ' + sevColor + '; border-radius: 0 8px 8px 0; padding: 12px 16px; margin-bottom: 10px;">' +
                            '<div class="d-flex justify-content-between align-items-start mb-1">' +
                            '<span style="font-weight:700; color:' + sevColor + '; font-size:13px;">' + sevIcon + ' ' + sev + '</span>' +
                            '<span style="font-size:11px; background:rgba(0,0,0,0.06); padding:2px 8px; border-radius:20px; color:#555;">' + typeLabel + '</span>' +
                            '</div>' +
                            '<div style="font-size:13px; color:#444; margin-bottom:4px;"><strong>Medicines:</strong> ' + meds + '</div>' +
                            '<div style="font-size:13px; color:#333; line-height:1.5;">' + expl + '</div>' +
                            '</div>';
                    });
                    $('#rxIssuesList').html(issuesHtml || '<p class="text-muted">No specific issues listed.</p>');
                }

                // --- Recommendations ---
                $('#rxRecommendationsText').text(data.recommendations || 'No specific recommendations.');
            },
            error: function(xhr) {
                $('#rxValidationLoading').hide();
                $('#rxValidationError').show();
                var msg = 'Failed to connect to AI service.';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.message) msg = resp.message;
                } catch(e) {}
                $('#rxValidationErrorMsg').text(msg + ' (HTTP ' + xhr.status + ')');
            }
        });
    }
</script>
