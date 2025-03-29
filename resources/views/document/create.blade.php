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
                                <form action="{{ route('document.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Type</b></label>
                                            <select name="type" class="form-control" required>
                                                <option value="" selected disabled>Select...</option>
                                                <option value="Request for Certificate of No Pending Case">Request for Certificate of No Pending Case</option>
                                                <option value="Invitation Letters">Invitation Letters</option>
                                                <option value="Employers' Notices">Employers' Notices</option>
                                                <option value="Complaints">Complaints</option>
                                                <option value="Report on Compliance">Report on Compliance</option>
                                                <option value="Inspection Requests">Inspection Requests</option>
                                                <option value="Certified True Copies">Certified True Copies</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <input type="text" name="status" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Origin</label>
                                            <textarea name="origin" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Forward To</label>
                                            <input type="text" name="forward_to" class="form-control" required>
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
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Save</button>
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
                                        <form action="{{ route('document.update', $document->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <label>Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="Request" {{ $document->type == 'Request' ? 'selected' : '' }}>Request</option>
                                                    <option value="Report" {{ $document->type == 'Report' ? 'selected' : '' }}>Report</option>
                                                </select>

                                                <label>Status</label>
                                                <input type="text" name="status" class="form-control" value="{{ $document->status }}" required>

                                                <label>Origin</label>
                                                <textarea name="origin" class="form-control" required>{{ $document->origin }}</textarea>

                                                <label>Forward To</label>
                                                <input type="text" name="forward_to" class="form-control" value="{{ $document->forward_to }}" required>

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
@endsection
