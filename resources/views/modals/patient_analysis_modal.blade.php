<!-- Patient Analysis Modal -->
<div class="modal fade" id="patientAnalysisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="fa fa-stethoscope mr-2"></i>Patient Clinical Analysis
                    <span id="cachedBadge" class="badge badge-warning ml-2" style="display:none; font-size: 11px;">Cached</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">

                {{-- Loading State --}}
                <div id="aiAnalysisLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="mt-3 text-primary">Generating Clinical Summary...</h5>
                    <p class="text-muted">Analyzing patient vitals, lab trends, medications, and history.</p>
                </div>

                <div id="aiAnalysisContent" style="display:none;">

                    {{-- Patient Summary --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2 analysis-section-header" style="border-bottom: 2px solid #007bff; padding-bottom: 6px; gap: 8px;">
                            <h6 class="font-weight-bold mb-0" style="font-size: 15px; color: #1a3a6b;">
                                <i class="fa fa-user-circle text-primary mr-1"></i> Patient Summary
                            </h6>
                            <button type="button" class="btn btn-outline-primary btn-sm analysis-tts-btn" id="aiSummarySpeakBtn" onclick="speakAnalysisSection('aiPatientSummaryText', this)">
                                <i class="fa fa-volume-up mr-1"></i> Voice
                            </button>
                        </div>
                        <p id="aiPatientSummaryText" class="mb-0" style="color: #2c3e50; font-size: 14.5px; line-height: 1.75;"></p>
                    </div>

                    {{-- AI-Generated Clinical Insights --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3 analysis-section-header" style="border-bottom: 2px solid #007bff; padding-bottom: 6px; gap: 8px;">
                            <h6 class="font-weight-bold mb-0" style="font-size: 15px; color: #1a3a6b;">
                                <i class="fa fa-lightbulb-o text-warning mr-1"></i> AI-Generated Clinical Insights
                            </h6>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <label for="aiAnalysisVoiceSpeed" class="mb-0 small text-muted">Speed</label>
                                <select id="aiAnalysisVoiceSpeed" class="form-control form-control-sm" style="width: 90px;">
                                    <option value="0.8">0.8x</option>
                                    <option value="1" selected>1.0x</option>
                                    <option value="1.2">1.2x</option>
                                    <option value="1.4">1.4x</option>
                                </select>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="aiAnalysisPauseBtn" onclick="toggleAnalysisSpeechPause()" disabled>
                                    <i class="fa fa-pause mr-1"></i> Pause
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="aiAnalysisStopBtn" onclick="stopAnalysisSpeech()" disabled>
                                    <i class="fa fa-stop mr-1"></i> Stop
                                </button>
                            </div>
                        </div>
                        <div id="aiInsightsList"></div>
                    </div>

                    {{-- Clinical Flags & Alerts (supplementary) --}}
                    <div id="aiFlagsSection" class="mb-4" style="display:none;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap: 8px;">
                            <h6 class="font-weight-bold mb-0" style="font-size: 14px; color: #856404;">
                                <i class="fa fa-exclamation-triangle text-warning mr-1"></i> Clinical Flags &amp; Alerts
                            </h6>
                            <button type="button" class="btn btn-outline-warning btn-sm analysis-tts-btn" id="aiFlagsSpeakBtn" onclick="speakAnalysisSection('aiFlagsList', this)">
                                <i class="fa fa-volume-up mr-1"></i> Voice
                            </button>
                        </div>
                        <ul id="aiFlagsList" class="list-unstyled mb-0"></ul>
                    </div>

                    {{-- AI Clinical Conclusion --}}
                    <div id="aiConclusionSection" class="mt-3 p-3" style="border: 1px solid #28a745; background-color: #f6fff7; border-radius: 6px; display:none;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap: 8px;">
                            <h6 class="font-weight-bold text-success mb-0" style="font-size: 14px;">
                                <i class="fa fa-user-md mr-1"></i> AI Clinical Conclusion
                            </h6>
                            <button type="button" class="btn btn-outline-success btn-sm analysis-tts-btn" id="aiConclusionSpeakBtn" onclick="speakAnalysisSection('aiConclusionText', this)">
                                <i class="fa fa-volume-up mr-1"></i> Voice
                            </button>
                        </div>
                        <div id="aiConclusionText" style="color: #155724; font-size: 14px; line-height: 1.6;"></div>
                    </div>

                    <div class="mt-3 pt-2 border-top text-right">
                        <p class="text-muted small mb-0">
                            <em>* This analysis is AI-generated. Always verify with clinical judgment before acting.</em>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="generateAnalysis(true)">
                    <i class="fa fa-refresh mr-1"></i> Refresh Analysis
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #aiInsightsList .insight-item {
        margin-bottom: 1rem;
        padding: 14px 16px;
        border-radius: 10px;
        border-left: 5px solid #d6dce5;
        background: #f8fafc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    #aiInsightsList .insight-title {
        color: #1a3a6b;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    #aiInsightsList .insight-detail {
        color: #333;
        font-size: 14px;
        line-height: 1.7;
    }
    #aiInsightsList .insight-detail ul {
        margin: 6px 0 8px 0;
        padding-left: 20px;
    }
    #aiInsightsList .insight-detail li {
        margin-bottom: 3px;
    }
    #aiInsightsList .insight-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }
    #aiInsightsList .insight-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }
    #aiInsightsList .insight-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    #aiInsightsList .insight-severity {
        font-size: 11px;
        padding: 3px 9px;
        border-radius: 999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    #aiInsightsList .insight-item.severity-high {
        background: #fff5f5;
        border-left-color: #dc3545;
    }
    #aiInsightsList .insight-item.severity-high .insight-title {
        color: #9f1d2c;
    }
    #aiInsightsList .insight-item.severity-high .insight-severity {
        background: #f8d7da;
        color: #842029;
    }
    #aiInsightsList .insight-item.severity-medium {
        background: #fffbea;
        border-left-color: #f0ad4e;
    }
    #aiInsightsList .insight-item.severity-medium .insight-title {
        color: #9a6700;
    }
    #aiInsightsList .insight-item.severity-medium .insight-severity {
        background: #fff3cd;
        color: #8a6d3b;
    }
    #aiInsightsList .insight-item.severity-low {
        background: #f2fbf5;
        border-left-color: #28a745;
    }
    #aiInsightsList .insight-item.severity-low .insight-title {
        color: #176b2f;
    }
    #aiInsightsList .insight-item.severity-low .insight-severity {
        background: #d4edda;
        color: #1e7e34;
    }
    #aiFlagsList .flag-item {
        display: flex;
        align-items: flex-start;
        padding: 7px 10px;
        margin-bottom: 6px;
        background: #fff8e1;
        border-left: 3px solid #ff9800;
        border-radius: 4px;
        font-size: 13.5px;
        color: #4a3800;
    }
    #aiFlagsList .flag-item i {
        margin-right: 8px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    #aiFlagsList .flag-item .flag-body {
        display: flex;
        flex-direction: column;
        gap: 4px;
        width: 100%;
    }
    #aiFlagsList .flag-item .flag-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    #aiFlagsList .flag-item .flag-severity {
        font-size: 11px;
        padding: 1px 6px;
        border-radius: 10px;
        text-transform: uppercase;
        font-weight: 700;
    }
    #aiFlagsList .flag-item .sev-critical { background: #f8d7da; color: #721c24; }
    #aiFlagsList .flag-item .sev-high { background: #ffe5b4; color: #7a4b00; }
    #aiFlagsList .flag-item .sev-moderate { background: #fff3cd; color: #6c5700; }
    #aiFlagsList .flag-item .sev-info { background: #d1ecf1; color: #0c5460; }
    #aiFlagsList .flag-item .flag-date {
        font-size: 11px;
        color: #7b6a38;
        white-space: nowrap;
    }
    .analysis-tts-btn.is-speaking {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }
    #aiInsightsList .analysis-tts-btn {
        padding: 2px 8px;
        line-height: 1.4;
    }
</style>

<script>
    let analysisSpeechUtterance = null;
    let analysisSpeechButton = null;
    let analysisSpeechPaused = false;

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
        $('#aiFlagsSection').hide();
        $('#aiConclusionText').html('');
        $('#aiConclusionSection').hide();
        stopAnalysisSpeech();

        let patientId = $('#patient_id').val();

        if (!patientId) {
            $('#aiAnalysisLoading').hide();
            $('#aiAnalysisContent').show();
            $('#aiPatientSummaryText').text("Error: Patient ID not found. Please reload the page.");
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

                if (response.status !== 'success') {
                    $('#aiPatientSummaryText').text('Analysis failed: ' + (response.message || 'Unknown error.'));
                    return;
                }

                if (response.cached) {
                    $('#cachedBadge').show();
                }

                // Patient Summary
                $('#aiPatientSummaryText').text(response.data.patient_summary || 'No summary available.');

                // AI-Generated Clinical Insights
                let insights = response.data.insights || [];
                let insightsHtml = '';
                if (insights.length > 0) {
                    insights.forEach(function(insight, index) {
                        let severity = normalizeInsightSeverity(insight.severity) || mapColorToSeverity(insight.color) || getInsightSeverity(insight);
                        let severityLabel = severity === 'high' ? 'High' : (severity === 'medium' ? 'Medium' : 'Low');
                        let insightId = `aiInsightDetail-${index}`;
                        insightsHtml += `
                            <div class="insight-item severity-${severity}" data-ai-color="${escapeHtml((insight.color || '').toString())}">
                                <div class="insight-header-row">
                                    <div class="insight-header-left">
                                        <div class="insight-title mb-0">${index + 1}. ${escapeHtml(insight.title || '')}</div>
                                        <span class="insight-severity">${severityLabel}</span>
                                    </div>
                                    <div class="insight-controls">
                                        <button type="button" class="btn btn-outline-primary btn-sm analysis-tts-btn" onclick="speakAnalysisSection('${insightId}', this)">
                                            <i class="fa fa-volume-up mr-1"></i> Voice
                                        </button>
                                    </div>
                                </div>
                                <div class="insight-detail" id="${insightId}">${insight.detail || ''}</div>
                            </div>`;
                    });
                } else {
                    insightsHtml = '<p class="text-muted small">No clinical insights generated.</p>';
                }
                $('#aiInsightsList').html(insightsHtml);

                // Clinical Flags & Alerts (shown only if flags exist)
                let flags = response.data.flags || [];
                let structuredFlags = response.data.flags_structured || [];
                if (flags.length > 0) {
                    let flagsHtml = '';
                    if (structuredFlags.length > 0) {
                        structuredFlags.forEach(function(flag) {
                            let severity = (flag.severity || 'info').toLowerCase();
                            let severityClass = 'sev-info';
                            if (severity === 'critical') severityClass = 'sev-critical';
                            else if (severity === 'high') severityClass = 'sev-high';
                            else if (severity === 'moderate') severityClass = 'sev-moderate';

                            let dateText = flag.latest_date ? `Last test: ${escapeHtml(flag.latest_date)}` : '';
                            flagsHtml += `
                                <li class="flag-item">
                                    <i class="fa fa-exclamation-circle text-warning"></i>
                                    <div class="flag-body">
                                        <div class="flag-header">
                                            <span class="flag-severity ${severityClass}">${escapeHtml(severity)}</span>
                                            ${dateText ? `<span class="flag-date">${dateText}</span>` : ''}
                                        </div>
                                        <div>${escapeHtml(flag.message || '')}</div>
                                    </div>
                                </li>`;
                        });
                    } else {
                        flags.forEach(function(flag) {
                            flagsHtml += `<li class="flag-item"><i class="fa fa-exclamation-circle text-warning"></i>${escapeHtml(flag)}</li>`;
                        });
                    }
                    $('#aiFlagsList').html(flagsHtml);
                    $('#aiFlagsSection').show();
                }

                // AI Clinical Conclusion (shown only if conclusion exists)
                let conclusion = (response.data.conclusion || '').trim();
                if (conclusion) {
                    $('#aiConclusionText').html(conclusion);
                    $('#aiConclusionSection').show();
                }
            },
            error: function(xhr) {
                $('#aiAnalysisLoading').hide();
                $('#aiAnalysisContent').show();
                $('#aiPatientSummaryText').text("Error connecting to AI service (" + xhr.status + "). Please try again.");
            }
        });
    }

    function escapeHtml(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function normalizeInsightSeverity(severity) {
        severity = (severity || '').toString().trim().toLowerCase();
        if (severity === 'high' || severity === 'medium' || severity === 'low') {
            return severity;
        }
        return null;
    }

    function mapColorToSeverity(color) {
        color = (color || '').toString().trim().toLowerCase();
        if (color === 'red') return 'high';
        if (color === 'yellow') return 'medium';
        if (color === 'green') return 'low';
        return null;
    }

    function getInsightSeverity(insight) {
        const title = ((insight && insight.title) || '').toLowerCase();
        const detail = ((insight && insight.detail) || '').toLowerCase().replace(/<[^>]*>/g, ' ');
        const combined = `${title} ${detail}`;

        const highSignals = [
            'alert',
            'worsened',
            'worsening',
            'requires clinical attention',
            'closer monitoring',
            'nephropathy',
            'high risk',
            'critical',
            'progression',
            'elevated inflammatory',
            'suboptimal long-term glycemic control'
        ];

        const lowSignals = [
            'within recommended targets',
            'within target ranges',
            'adequate management',
            'improvement',
            'improving',
            'stable',
            'controlled'
        ];

        if (highSignals.some(function(signal) { return combined.indexOf(signal) !== -1; })) {
            return 'high';
        }

        if (lowSignals.some(function(signal) { return combined.indexOf(signal) !== -1; })) {
            return 'low';
        }

        if (title.indexOf('overall ai interpretation') !== -1) {
            if (combined.indexOf('worsening') !== -1 || combined.indexOf('nephropathy') !== -1) {
                return 'high';
            }
            if (combined.indexOf('within target') !== -1) {
                return 'medium';
            }
        }

        return 'medium';
    }

    function speakAnalysisSection(elementId, button) {
        if (!('speechSynthesis' in window)) {
            alert("Your browser doesn't support Text to Speech.");
            return;
        }

        const element = document.getElementById(elementId);
        if (!element) {
            return;
        }

        const text = extractSpeechText(element);
        if (!text) {
            return;
        }

        if (analysisSpeechButton === button && speechSynthesis.speaking && !speechSynthesis.paused) {
            stopAnalysisSpeech();
            return;
        }

        stopAnalysisSpeech(false);

        const utterance = new SpeechSynthesisUtterance(text);
        const voices = window.speechSynthesis.getVoices();
        const selectedVoice =
            voices.find(function(voice) {
                return voice.name.includes('Google') && voice.lang === 'en-IN';
            }) ||
            voices.find(function(voice) {
                return voice.lang === 'en-IN';
            }) ||
            voices[0];

        if (selectedVoice) {
            utterance.voice = selectedVoice;
        }

        utterance.rate = parseFloat($('#aiAnalysisVoiceSpeed').val() || '1');
        utterance.pitch = 1;

        utterance.onstart = function() {
            analysisSpeechUtterance = utterance;
            analysisSpeechButton = button;
            analysisSpeechPaused = false;
            markActiveSpeechButton(button, true);
            updateAnalysisSpeechControls();
        };

        utterance.onend = function() {
            clearAnalysisSpeechState();
        };

        utterance.onerror = function() {
            clearAnalysisSpeechState();
        };

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    }

    function extractSpeechText(element) {
        const cloned = element.cloneNode(true);
        $(cloned).find('button').remove();
        return (cloned.innerText || cloned.textContent || '').replace(/\s+/g, ' ').trim();
    }

    function toggleAnalysisSpeechPause() {
        if (!('speechSynthesis' in window) || !window.speechSynthesis.speaking) {
            return;
        }

        if (window.speechSynthesis.paused) {
            window.speechSynthesis.resume();
            analysisSpeechPaused = false;
        } else {
            window.speechSynthesis.pause();
            analysisSpeechPaused = true;
        }

        updateAnalysisSpeechControls();
    }

    function stopAnalysisSpeech(resetButton = true) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }

        if (resetButton) {
            clearAnalysisSpeechState();
        } else {
            if (analysisSpeechButton) {
                markActiveSpeechButton(analysisSpeechButton, false);
            }
            analysisSpeechUtterance = null;
            analysisSpeechButton = null;
            analysisSpeechPaused = false;
            updateAnalysisSpeechControls();
        }
    }

    function clearAnalysisSpeechState() {
        if (analysisSpeechButton) {
            markActiveSpeechButton(analysisSpeechButton, false);
        }
        analysisSpeechUtterance = null;
        analysisSpeechButton = null;
        analysisSpeechPaused = false;
        updateAnalysisSpeechControls();
    }

    function markActiveSpeechButton(button, active) {
        $('.analysis-tts-btn').removeClass('is-speaking');
        $('.analysis-tts-btn i').removeClass('fa-stop').addClass('fa-volume-up');
        $('.analysis-tts-btn').each(function() {
            const labelNode = this.childNodes[this.childNodes.length - 1];
            if (labelNode && labelNode.nodeType === Node.TEXT_NODE) {
                labelNode.textContent = ' Voice';
            }
        });

        if (active && button) {
            $(button).addClass('is-speaking');
            $(button).find('i').removeClass('fa-volume-up').addClass('fa-stop');
            const labelNode = button.childNodes[button.childNodes.length - 1];
            if (labelNode && labelNode.nodeType === Node.TEXT_NODE) {
                labelNode.textContent = ' Stop';
            }
        }
    }

    function updateAnalysisSpeechControls() {
        const canControl = 'speechSynthesis' in window && (window.speechSynthesis.speaking || analysisSpeechPaused);
        $('#aiAnalysisPauseBtn').prop('disabled', !canControl);
        $('#aiAnalysisStopBtn').prop('disabled', !canControl);
        $('#aiAnalysisPauseBtn').html(window.speechSynthesis.paused
            ? '<i class="fa fa-play mr-1"></i> Resume'
            : '<i class="fa fa-pause mr-1"></i> Pause');
    }

    $('#aiAnalysisVoiceSpeed').on('change', function() {
        if (analysisSpeechButton && analysisSpeechUtterance && window.speechSynthesis.speaking) {
            const activeButton = analysisSpeechButton;
            const activeElementId = $(activeButton).attr('onclick')?.match(/'([^']+)'/)?.[1];
            if (activeElementId) {
                speakAnalysisSection(activeElementId, activeButton);
            }
        }
    });

    $('#patientAnalysisModal').on('hidden.bs.modal', function() {
        stopAnalysisSpeech();
    });
</script>
