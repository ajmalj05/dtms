<!-- Patient Analysis Modal -->
<div class="modal fade" id="patientAnalysisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Patient Clinical Analysis <span id="cachedBadge" class="badge badge-warning ml-2" style="display:none; font-size: 12px;">Cached</span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="aiAnalysisLoading" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="mt-3">Generating clinical summary...</h5>
                    <p class="text-muted">Analyzing patient vitals, medications, and history.</p>
                </div>
                <div id="aiAnalysisContent" style="display:none;">

                    {{-- Patient Summary --}}
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-2" style="font-size: 15px; border-bottom: 2px solid #007bff; padding-bottom: 6px;">
                            <i class="fa fa-user-circle text-primary mr-1"></i> Patient Summary
                        </h6>
                        <p id="aiPatientSummaryText" class="mb-0" style="color: #2c3e50; font-size: 15px; line-height: 1.7;"></p>
                    </div>

                    {{-- AI-Generated Clinical Insights --}}
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark mb-3" style="font-size: 15px; border-bottom: 2px solid #007bff; padding-bottom: 6px;">
                            <i class="fa fa-lightbulb-o text-warning mr-1"></i> AI-Generated Clinical Insights
                        </h6>
                        <div id="aiInsightsList"></div>
                    </div>

                    {{-- Clinical Flags & Alerts --}}
                    <div id="aiFlagsSection" class="mt-2 mb-4">
                        <h6 class="mb-3 text-dark"><i class="fa fa-flag text-danger"></i> Clinical Flags &amp; Alerts</h6>
                        <ul id="aiFlagsList" class="list-group"></ul>
                    </div>

                    {{-- AI Clinical Conclusion --}}
                    <div class="mt-3 p-3" style="border: 2px solid #28a745; background-color: #f6fff7; border-radius: 8px;">
                        <h6 class="font-weight-bold text-success mb-2">
                            <i class="fa fa-user-md"></i> AI Clinical Conclusion
                        </h6>
                        <div id="aiConclusionText" class="mb-0" style="color: #155724; font-size: 15px; line-height: 1.6;">
                            Analysis in progress...
                        </div>
                    </div>

                    <div class="mt-3 pt-2 border-top text-right">
                        <p class="text-muted small mb-0">
                            <em>* This analysis is AI-generated. Verify with clinical judgment.</em>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="generateAnalysis(true)"><i class="fa fa-refresh"></i> Refresh Analysis</button>
            </div>
        </div>
    </div>
</div>

<script>
    // AI Patient Analysis Logic
    function openPatientAnalysis() {
        $('#patientAnalysisModal').modal('show');
        generateAnalysis();
    }

    function generateAnalysis(isRefresh = false) {
        $('#aiAnalysisLoading').show();
        $('#aiAnalysisContent').hide();
        $('#cachedBadge').hide();
        $('#aiPatientSummaryText').text('');
        $('#aiInsightsList').empty();
        $('#aiFlagsList').empty();
        $('#aiConclusionText').text('Analysis in progress...');

        let patientId = $('#patient_id').val();

        if (!patientId) {
            $('#aiAnalysisLoading').hide();
            $('#aiAnalysisContent').show();
            $('#aiPatientSummaryText').text("Error: Patient ID not found. Please reload.");
            return;
        }

        $.ajax({
            url: "{{ route('ai.summary') }}",
            type: "POST",
            data: {
                patient_id: patientId,
                force_refresh: isRefresh,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#aiAnalysisLoading').hide();
                $('#aiAnalysisContent').show();

                if (response.status === 'success') {
                    if (response.cached) {
                        $('#cachedBadge').show();
                    }

                    // Patient Summary (prose paragraph)
                    $('#aiPatientSummaryText').text(response.data.patient_summary || 'No summary available.');

                    // AI-Generated Clinical Insights (numbered sections)
                    let insightsHtml = '';
                    let insights = response.data.insights || [];
                    if (insights.length > 0) {
                        insights.forEach(function(insight, index) {
                            insightsHtml += `
                                <div class="mb-3">
                                    <h6 class="font-weight-bold mb-1" style="color: #1a3a6b; font-size: 14px;">
                                        ${index + 1}. ${insight.title || ''}
                                    </h6>
                                    <p class="mb-0" style="color: #333; font-size: 14px; line-height: 1.65; padding-left: 16px;">${insight.detail || ''}</p>
                                </div>`;
                        });
                    } else {
                        insightsHtml = '<p class="text-muted">No clinical insights generated.</p>';
                    }
                    $('#aiInsightsList').html(insightsHtml);

                    // Clinical Flags & Alerts
                    let flagsHtml = '';
                    if (response.data.flags && response.data.flags.length > 0) {
                        response.data.flags.forEach(function(flag) {
                            flagsHtml += `<li class="list-group-item list-group-item-warning mb-1" style="border-left: 4px solid #ff9800;">
                                <i class="fa fa-exclamation-circle text-warning mr-2"></i> ${flag}
                            </li>`;
                        });
                    } else {
                        flagsHtml = `<li class="list-group-item list-group-item-success" style="border-left: 4px solid #4caf50;">
                            <i class="fa fa-check-circle text-success mr-2"></i> No critical flags identified.
                        </li>`;
                    }
                    $('#aiFlagsList').html(flagsHtml);

                    // Populate Conclusion
                    let conclusion = response.data.conclusion || 'No specific directive provided.';
                    $('#aiConclusionText').html(conclusion);

                } else {
                    $('#aiPatientSummaryText').text('Analysis failed: ' + (response.message || 'Unknown error.'));
                }
            },
            error: function(xhr) {
                $('#aiAnalysisLoading').hide();
                $('#aiAnalysisContent').show();
                $('#aiPatientSummaryText').text("Error connecting to AI service (" + xhr.status + "). Please try again.");
            }
        });
    }
</script>
