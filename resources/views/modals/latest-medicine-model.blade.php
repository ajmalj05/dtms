<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="latest-medicine-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="latest-medicine-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Latest Prescribed Medicines</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Tablet Type</th>
                                    <th>Dosage</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="latest-medicine-body">
                                <!-- Medicine data will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>




<style>

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>

<script>console.log("JavaScript file is loaded!");

function getLatestMedicines(uhidno) {
    console.log("Function Triggered: uhidno =", uhidno);

    $.ajax({
        url: "{{ route('getLatestMedicine') }}",
        type: "POST",
        data: { uhidno: uhidno, _token: "{{ csrf_token() }}" }, // Include CSRF token
        success: function (response) {
            console.log("AJAX Success Response:", response);
            var html = "";
            
            if (response.data && response.data.length > 0) {
                response.data.forEach(function (medicine) {
                    html += `
                        <tr>
                            <td>${medicine.medicine_name}</td>
                            <td>${medicine.tablet_type_name}</td>
                            <td>${medicine.dose || 'N/A'}</td>
                            <td>${medicine.remarks || 'N/A'}</td>
                        </tr>`;
                });
            } else {
                html = `<tr><td colspan="4" class="text-center">No Medicines Found</td></tr>`;
            }

            $("#latest-medicine-body").html(html);
            $("#latest-medicine-modal").modal("show");
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#latest-medicine-body").html('<tr><td colspan="4" class="text-center text-danger">Failed to fetch medicine data.</td></tr>');
        }
    });
}




</script>
