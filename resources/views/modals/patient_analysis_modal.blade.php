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
                    <div class="mb-3 p-2" style="border-bottom: 2px solid #f8f9fa;">
                        <h6 class="text-dark font-weight-bold"><i class="fa fa-info-circle text-primary"></i> Clinical Overview</h6>
                        <p id="aiOverviewText" class="mb-0" style="color: #2c3e50; font-size: 16px;"></p>
                    </div>

                    <div class="alert alert-info" role="alert" style="background-color: #f0f7ff; border-left: 5px solid #007bff; border-radius: 8px;">
                        <h6 class="alert-heading text-primary font-weight-bold"><i class="fa fa-history"></i> Patient Context & History</h6>
                        <p id="aiSummaryText" class="mb-0 mt-2" style="line-height: 1.6; font-size: 15px; color: #333;"></p>
                    </div>
                    
                    <div id="aiFlagsSection" class="mt-4">
                        <h6 class="mb-3 text-dark"><i class="fa fa-flag text-danger"></i> Clinical Flags & Alerts</h6>
                        <ul id="aiFlagsList" class="list-group">
                        </ul>
                    </div>

                    <!-- AI Conclusion Section -->
                    <div class="mt-4 p-3" style="border: 2px solid #28a745; background-color: #f6fff7; border-radius: 8px;">
                        <h6 class="font-weight-bold text-success mb-2">
                            <i class="fa fa-user-md"></i> AI Clinical Conclusion
                        </h6>
                         <div id="aiConclusionText" class="mb-0" style="color: #155724; font-size: 15px; line-height: 1.6;">
                            Analysis in progress...
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top text-right">
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
        $('#aiOverviewText').text('');
        $('#aiSummaryText').text('');
        $('#aiFlagsList').empty();
        $('#aiConclusionText').text('Analysis in progress...');
        
        let patientId = $('#patient_id').val();
        
        if (!patientId) {
             $('#aiAnalysisLoading').hide();
             $('#aiAnalysisContent').show();
             $('#aiSummaryText').text("Error: Patient ID not found. Please reload.");
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
                    
                    $('#aiOverviewText').text(response.data.overview || 'N/A');
                    
                    // improved bullet list formatting
                    let rawSummary = response.data.summary || 'N/A';
                    let summaryHtml = '';
                    
                    // Split by various newline formats and bullet markers
                    let lines = rawSummary.split(/\n|\\n|•/);
                    let listItems = [];
                    
                    lines.forEach(line => {
                        let cleanLine = line.trim();
                        // Remove potential existing bullet characters from the start (-, *, •)
                        cleanLine = cleanLine.replace(/^[-*•]\s*/, '');
                        
                        if (cleanLine.length > 5) { // Only add if it's a meaningful point
                            listItems.push(`<li class="mb-2" style="list-style-type: disc; margin-left: 20px;">${cleanLine}</li>`);
                        }
                    });
                    
                    if (listItems.length > 0) {
                         summaryHtml = '<ul style="padding-left: 0; margin-bottom: 0;">' + listItems.join('') + '</ul>';
                    } else {
                         summaryHtml = `<p class="text-muted italic">${rawSummary}</p>`;
                    }
                    
                    $('#aiSummaryText').html(summaryHtml);
                    
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
                    $('#aiOverviewText').text('Analysis failed: ' + (response.message || 'Unknown error.'));
                }
            },
            error: function(xhr) {
                 $('#aiAnalysisLoading').hide();
                 $('#aiAnalysisContent').show();
                 $('#aiSummaryText').text("Error connecting to AI service (" + xhr.status + "). Please try again.");
            }
        });
    }
</script>
