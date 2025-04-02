@extends('layouts.app')

@section('content')
{{-- <div class="container-fluid">

   <h1>This is admin</h1>

</div> --}}
<style>
    /* You can use nth-child(1), nth-child(2), etc., to target specific columns */
    /* For this example, let's adjust the width of the first and second columns */
    td:nth-child(1) {
    width: 80px;
    }
    td:nth-child(2) {  
    width: 300px; 
    }
    td:nth-child(3) {
    width: 350px;
    }
    td:nth-child(4) {
    width: 200px;
    }
    td:nth-child(5) {
    width: 100px;
    }
    td:nth-child(6) {
    width: 130px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container-fluid">

    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div> --}}
    <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- <h4 class="card-title">Default Datatable</h4>
                                <p class="card-title-desc">DataTables has most features enabled by
                                    default, so all you need to do to use it with your own tables is to call
                                    the construction function: <code>$().DataTable();</code>.
                                </p> --}}





                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>CODE</th>
                                            <th>TYPE</th>
                                            <th>STATUS NAME</th>
                                            <th>ORIGIN</th>
                                            <th>TO</th>
                                            <th>FORWARDED BY</th>
                                            <th>DATE/TIME</th>
                                            <th>REMARKS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documentTrackings as $documentTracking)
                                        <tr>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->document_code : 'N/A' }}</td>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->type : 'N/A' }}</td>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->status_name : 'N/A' }}</td>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->origin : 'N/A' }}</td>
                                            <td>{{ strtoupper($documentTracking->user->office_division) }}<br>- {{ Str::ucfirst(strtolower($documentTracking->user->first_name)) }} {{ Str::ucfirst(strtolower(Str::substr($documentTracking->user->middle_name, 0, 1))) }}. {{ Str::ucfirst(strtolower($documentTracking->user->last_name)) }}</td>
                                            <td>{{ strtoupper($documentTracking->office_division) }}</td>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->created_at : 'N/A' }}</td>
                                            <td>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->remarks : 'N/A' }}</td>
                                            <td>
                                                <button class="ri ri-eye-fill btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal-{{ $documentTracking->id }}"></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Move all modal dialogs outside the table -->
                                @foreach ($documentTrackings as $documentTracking)
                                <div class="modal" id="myModal-{{ $documentTracking->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title secondary">Document Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('document.update', $documentTracking->id) }}" method="POST" enctype="multipart/form-data">
                                                @method('PATCH')
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <div class="mb-4">
                                                            <label class="form-label"><b>Type</b></label>
                                                            <input type="text" value="{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->type : 'N/A' }}" readonly class="form-control">
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label">Document Code</label>
                                                            <input type="text" value="{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->document_code : 'N/A' }}" class="form-control" readonly>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label">Origin</label>
                                                            <textarea class="form-control" readonly>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->origin : 'N/A' }}</textarea>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label">Subject</label>
                                                            <textarea class="form-control" readonly>{{ $documentTracking->documentDetail ? $documentTracking->documentDetail->subject : 'N/A' }}</textarea>
                                                        </div>
                                                        <div class="mb-4">
                                                            <input type="text" value="received" name="status" class="form-control" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Received</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

</div> 
@endsection
