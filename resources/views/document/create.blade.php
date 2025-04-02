@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    td:nth-child(1) { width: 80px; }
    td:nth-child(2) { width: 300px; }
    td:nth-child(3) { width: 350px; }
    td:nth-child(4) { width: 350px; }
    td:nth-child(5) { width: 100px; }
    td:nth-child(6) { width: 50px; }

    .modal-fullscreen .modal-content {
        height: 80vh;
        width: 40vw;
        margin: 20px auto;
    }

    .status-list-container {
        max-height: 80vh;
        overflow-y: auto;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#myModal">Create Documents</button>
                    </div>
                    
                    <div class="modal" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title secondary">Action</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form id="saveDocumentForm" class="form-horizontal mt-3" method="POST" action="{{ route('document.store') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="modal-body">
                                        <div id="response-message"></div>
                                        <div class="mb-3">
                                            <label class="form-label"><b>Document Code</b></label>

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="toggleMode" checked>
                                                <label class="form-check-label" for="toggleMode">Auto</label>
                                            </div>

                                            <input type="text" id="document_code" name="document_code" class="form-control" disabled />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="type"><b>Type</b></label>
                                            <select id="type" name="type" class="form-control" required>
                                                <option value="" selected disabled>Select...</option>
                                                <option value="Purchase Request (PR)">Purchase Request (PR)</option>
                                                <option value="BAC Resolution Award">BAC Resolution Award</option>
                                                <option value="Notice of Award">Notice of Award</option>
                                                <option value="Notice to Proceed">Notice to Proceed</option>
                                                <option value="Contract Agreement">Contract Agreement</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="statusSelectAdd" class="form-label">Status</label>
                                            <select onclick="setStatusSelect('add')" id="statusSelectAdd" name="status" class="form-control" data-bs-toggle="modal" data-bs-target="#statusModal" data-mode="add">
                                                <option value="" selected disabled>Select Status</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="origin" class="form-label">Origin</label>
                                            <textarea id="origin" name="origin" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="forward_to" class="form-label">Forward To</label>
                                            <select id="forward_to" type="text" name="forward_to" class="form-control" required>
                                                <option value="" selected disabled>Choose Office</option>
                                                <option value="records and archives unit">RECORDS and ARCHIVES Unit</option>
                                                <option value="bids and awards unit">BIDS and AWARDS Unit</option>
                                                <option value="payments unit">PAYMENTS Unit</option>
                                                <option value="procurement unit">PROCUREMENT Unit</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status Name</label>
                                            <input type="text" name="status_name" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="remarks" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success" id="saveDocument">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive">
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>TYPE</th>
                                <th>STATUS</th>
                                <th>ORIGIN</th>
                                <th>FORWARD TO</th>
                                <th>STATUS NAME</th>
                                <th>REMARKS</th>
                                <th>DATE</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                            <tr>
                                <td>{{ $document->document_code }}</td>
                                <td>{{ $document->type }}</td>
                                <td>{{ $document->status }}</td>
                                <td>{{ $document->origin }}</td>
                                <td>{{ $document->forward_to }}</td>
                                <td>{{ $document->status_name }}</td>
                                <td>{{ $document->remarks }}</td>
                                <td>{{ $document->created_at }}</td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a class="btn btn-warning" href="{{ route('document.pdf_view', $document->id) }}" target="_blank">Print</a>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $document->id }}">Edit</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal" id="editModal-{{ $document->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('document.updateEdit', $document->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <label>Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="Purchase Request (PR)" {{ $document->type == 'Purchase Request (PR)' ? 'selected' : '' }}>Purchase Request (PR)</option>
                                                    <option value="BAC Resolution Award" {{ $document->type == 'BAC Resolution Award' ? 'selected' : '' }}>BAC Resolution Award</option>
                                                    <option value="Notice of Award" {{ $document->type == 'Notice of Award' ? 'selected' : '' }}>Notice of Award</option>
                                                    <option value="Notice to Proceed" {{ $document->type == 'Notice to Proceed' ? 'selected' : '' }}>Notice to Proceed</option>
                                                    <option value="Contract Agreement" {{ $document->type == 'Contract Agreement' ? 'selected' : '' }}>Contract Agreement</option>
                                                </select>

                                                <label for="statusSelectEdit" class="form-label">Status</label>
                                                <select id="statusSelectEdit" onclick="setStatusSelect('edit')" name="status" class="form-control" data-bs-toggle="modal" data-bs-target="#statusModal" data-mode="edit">
                                                    <option value="{{ $document->status}}" selected>{{ $document->status}}</option>
                                                </select>

                                                <label>Origin</label>
                                                <textarea name="origin" class="form-control" required>{{ $document->origin }}</textarea>

                                                <label for="forward_to" class="form-label">Forward To</label>
                                                <select id="forward_to" type="text" name="forward_to" class="form-control" required>
                                                    <option value="records and archives unit" {{ $document->type == 'records and archives unit' ? 'selected' : '' }}>RECORDS and ARCHIVES Unit</option>
                                                    <option value="bids and awards unit" {{ $document->type == 'bids and awards unit' ? 'selected' : '' }}>BIDS and AWARDS Unit</option>
                                                    <option value="payments unit" {{ $document->type == 'payments unit' ? 'selected' : '' }}>PAYMENTS Unit</option>
                                                    <option value="procurement unit" {{ $document->type == 'procurement unit' ? 'selected' : '' }}>PROCUREMENT Unit</option>
                                                </select>

                                                <label>Status Name</label>
                                                <input type="text" name="status_name" class="form-control" value="{{ $document->status_name }}" required>

                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control" required>{{ $document->remarks }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Select Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="statusSearch" class="form-control mb-2" placeholder="Search status...">
                <div class="status-list-container">
                    <ul id="statusList" class="list-group">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer>
    let statusSelect = "add";
    const $statusList = $("#statusList");
    let $statusSelect = statusSelect === "add" ? $("#statusSelectAdd") : $("#statusSelectEdit");
    const $statusSearch = $("#statusSearch");


    const setStatusSelect = (setStatus) => {
        statusSelect = setStatus;
    }

    $(document).ready(function () {
        const statusOptions = [
            "Cancelled",
            "Received and forwarded to Pre-Screening",
            "Annual Procurement Plan was created",
            "APP Quarterly is generated",
            "APP Quarterly : Bidders Quoted",
            "APP Quarterly : Bidder Awarded",
            "Received for Public Bidding",
            "Item/Project Included for Procurement through Public Bidding",
            "BAC Deliberation",
            "Pre-Procurement",
            "Pre-Bid",
            "Preparation of Bidding Documents",
            "Advertisement Period (Start)",
            "Posting at PhilGEPS and PGBh Website for Pre-Bidding",
            "Advertisement Period (Posting to PHILGEPS)",
            "Posting of Bid Opportunities in Conspicuous Places",
            "Advertisement Period (Preparation of Notices)",
            "Inclusion in Notice of Bid Conference",
            "Preparation of Bid Documents",
            "Pre-Bid Conference",
            "For Supplemental Bid Bulletin at PhilGEPS, PGBh Website, and etc.",
            "Selling of Bid Documents to Prospective Supplier",
            "Bidding Documents Available for Sale",
            "Advertisement Period (End)",
            "Bid Opening and Bid Evaluation",
            "Bid Opening",
            "First Failure of Bidding, for Rebidding",
            "Preparation of Resolution for Rebidding",
            "Resolution for Rebidding for Signature/Approval",
            "Resolution for Rebidding Duly Approved",
            "Posting at PhilGEPS and PGBh Website for Rebidding",
            "Re-advertisement Period (Start)",
            "Resolution for Negotiated Mode of Procurement",
            "Re-Advertisement Period (Posting to PHILGEPS)",
            "Post-Qualification",
            "Notice of Post-Disqualification for Lowest Bidder, for Rebidding",
            "Notice of Post-Disqualification, for Rebidding",
            "Notice of Post-Disqualification, for Negotiated Mode",
            "Notice of Successful Post-Qualification",
            "Re-Advertisement Period (Preparation of Notices)",
            "Re-Advertisement Period (End)",
            "Preparation of Resolution of Award",
            "Resolution of Award for Signature",
            "Resolution of Award for Approval",
            "Notice of Award for Receipt of Winning Supplier",
            "Re-Bid Opening",
            "Notice of Award Duly Received by Winning Supplier",
            "Post Qualification Process",
            "Resolution for Alternative (2nd Failure)",
            "Notice of Award Posting at PhilGEPS and PGBh Website",
            "Performance Bond Received from Supplier",
            "Preparation of Contract",
            "Contract for Signature of Winning Supplier",
            "Contract for Signature/Approval of HOPE",
            "Contract for Notarization",
            "Contract Duly Notarized",
            "Letter Before Post Qualification (Forwarded)",
            "Preparation of Notice to Proceed",
            "Notice to Proceed for Approval",
            "Notice to Proceed for Receipt of Winning Supplier",
            "Notice to Proceed Duly Received by Winning Supplier",
            "Letter Before Post Qualification (Received)",
            "Letter Before Post Qualification Preparation (Received)",
            "Contract and Notice to Proceed Posting at PhilGEPS",
            "Bid Evaluation and Post Qualification Preparation",
            "Letter Before Post Qualification Preparation (Forwarded)",
            "Item for Delivery / Start of Project",
            "Bid Evaluation and Post Qualification Preparation",
            "Bid Evaluation and Post Qualification Signature",
            "Bid Evaluation and Post Qualification (Forwarded to TWG)",
            "Bid Evaluation and Post Qualification Signature (Received)",
            "Abstract and BAC Resolution of Award (Released)",
            "Abstract and BAC Resolution for Award Preparation",
            "Abstract and BAC Resolution of Award (Received)",
            "Bid Awarding (BAC Signature)",
            "Abstract and BAC Resolution of Award (Released)",
            "Abstract and BAC Resolution of Award (Received)",
            "Abstract and BAC Resolution of Award (Released)",
            "Abstract and BAC Resolution of Award (Released)",
            "Abstract and BAC Resolution of Award (Received for GO)",
            "Archiving at Records Section",
            "Abstract and BAC Resolution of Award (Released)",
            "Change Procurement Type",
            "Abstract and BAC Resolution of Award Approval Date",
            "Abstract and BAC Resolution of Award (Received)",
            "Notice of Award Preparation & Review (Forwarded)",
            "Notice of Award Preparation & Review (Received)",
            "Notice of Award (Released and Forwarded to GO)",
            "Notice of Award (Received for GO Signature)",
            "Notice of Award (Released and Forwarded to BAC)",
            "Notice of Award Approval Date",
            "Notice of Award (Received from GO)",
            "Notice of Award (Released and forwarded to Supplier)",
            "Notice of Award (Received Supplier Signature)",
            "Posting to PHILGEPS",
            "Purchase Order/Contract & Notice to Proceed Generation",
            "Purchase Order/Contract and Notice to Proceed",
            "Released Approved Documents to End-User"
        ].sort();

        populateStatusList = () => {
            $statusList.empty();
            $.each(statusOptions, function (index, status) {
                let $li = $("<li>")
                    .addClass("list-group-item list-group-item-action")
                    .text(status)
                    .css("cursor", "pointer")
                    .on("click", function (e) {
                        e.stopPropagation();

                        let $currentStatusSelect = statusSelect === 'add' ? $("#statusSelectAdd") : $("#statusSelectEdit");
                        $currentStatusSelect.html(`<option value="${status}" selected>${status}</option>`);
                        $("#statusModal").modal("hide");

                        if (statusSelect === 'add') {
                            $("#myModal").modal("show");
                        } else {
                            const documentId = "{{ $document->id }}";
                            $("#editModal-" + documentId).modal("show");
                        }
                    });

                $statusList.append($li);
            });
        };


        populateStatusList();

        $statusSearch.on("keyup", function () {
            let searchText = $(this).val().toLowerCase();
            $("#statusList li").each(function () {
                let text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchText));
            });
        });

        $statusSelect.on("click", function () {
            $("#statusModal").modal("show");
        });
    });

    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $("#saveDocument").on("click", function (e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('document.store') }}",
                method: "POST",
                data: $("#saveDocumentForm").serialize(),
                success: function (response) {
                    $("#response-message").html(
                        `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Document Successfully Created
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                    );

                    $("#saveDocumentForm")[0].reset();
                    
                    setTimeout(function () {
                        $("#myModal").modal("hide");
                    }, 1100);

                    setTimeout(function () {
                        window.location.href = "{{ route('document.received') }}";
                    }, 1100);
                },
                error: function (xhr) {
                    let errorMessage = "Something went wrong";
                    console.log(xhr.responseJSON);
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $("#response-message").html(
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                    );
                }
            });
        });
    });

    $(document).ready(function() {
        $('#toggleMode').change(function() {
            if ($(this).is(':checked')) {
                $('#document_code').prop('disabled', true).val('');
            } else {
                $('#document_code').prop('disabled', false);
            }
        });
    });
</script>
@endsection
