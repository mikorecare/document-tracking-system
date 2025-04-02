<?php
namespace App\Http\Controllers;

use App\Models\DocumentDetail;
use App\Models\DocumentTrace;
use App\Models\DocumentTracking;
use App\Models\Outgoing;
use App\Models\ReceivedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Node\Block\Document;
use RealRashid\SweetAlert\Facades\Alert;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function find(Request $request)
    {
        //     $request->validate([
        //       'query'=>'required|min:2'
        //    ]);

        // dd($request->input('query'));

        try {
            $search_text = $request->input('query');

            $documentDetail = DocumentDetail::where('document_code', $search_text)->first();

            if ($documentDetail) {
                $documentTraces = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->get();
                $documentLatest = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->latest()->first();
                $data           = $documentLatest->user->office_division;
                // throw new Exception('Something went wrong');
                $documentTracking = DocumentTracking::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->first();
                if ($data == $documentTracking->office_division && $documentTracking->status == "incoming") {
                    $documentTracking = null;
                }

            } else {
                Alert::error('oppss', 'No record found...');
                return view('document.tracked');
            }
        } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('document.tracked');
        }

        return view('document.tracked', compact('documentTraces', 'documentTracking', 'documentDetail'));
    }

    public function find2(Request $request)
    {
        //     $request->validate([
        //       'query'=>'required|min:2'
        //    ]);

        // dd($request->input('query'));

        try {
            $search_text = $request->input('query');

            $documentDetail = DocumentDetail::where('document_code', $search_text)->first();

            if ($documentDetail) {
                $documentTraces = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->get();
                $documentLatest = DocumentTrace::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->latest()->first();
                $data           = $documentLatest->user->office_division;
                // throw new Exception('Something went wrong');
                $documentTracking = DocumentTracking::where('document_detail_id', $documentDetail->id)->with('user', 'documentDetail')->first();
                if ($data == $documentTracking->office_division && $documentTracking->status == "incoming") {
                    $documentTracking = null;
                }
            } else {
                Alert::error('oppss', 'No record found...');
                return view('tracked');
            }
        } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('tracked');
        }

        return view('tracked', compact('documentTraces', 'documentTracking', 'documentDetail'));
    }

    public function dts()
    {
        return view('tracked');
    }

    public function createPDF(string $id)
    {
        $data = DocumentDetail::where('id', $id)->first();

        return view('document.pdf_view', compact('data'));
        // $pdf = Pdf::loadView('document.pdf_view', compact('data'))->setPaper('a4', 'landscape')->setWarnings(false);

        // return $pdf->stream();
    }

    public function allDocuments()
    {
        try {
            $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            if ($documentTrackings->isEmpty()) {
                Alert::info('No Documents Found', 'There are no documents in the system.');
            }

            return view('document.received', compact('documentTrackings'));
        } catch (\Exception $e) {
            \Log::error("Error fetching all documents: " . $e->getMessage());
            return redirect()->route('document.received')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    public function received()
    {
        try {
            $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            // No need to add placeholder objects when empty, just pass the collection (empty or not)
            if ($documentTrackings->isEmpty()) {
                $documentTrackings = collect(); // Pass empty collection directly
            }

            return view('document.received', compact('documentTrackings'));
        } catch (\Exception $e) {
            \Log::error("Error fetching received documents: " . $e->getMessage());
            return redirect()->route('document.received')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    public function incoming()
    {
        try {
            $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            return view('document.incoming', compact('documentTrackings'));
        } catch (\Exception $e) {
            return redirect()->route('document.incoming')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    public function receivedHistory()
    {
        try {
            $query = DocumentTracking::where('status', 'released')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $receivedHistories = $query->get();

            return view('document.received_histories', [
                'receivedHistories' => $receivedHistories,
                'message'           => $receivedHistories->isEmpty() ? 'No received histories found.' : null,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('document.receivedHistory')->with('error', 'An error occurred while fetching the histories.');
        }
    }

    public function outgoing()
    {
        try {
            $query = Outgoing::with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            if ($documentTrackings->isEmpty()) {
                $documentTrackings = collect();
            }

            return view('document.outgoing', compact('documentTrackings'));

        } catch (\Exception $e) {
            \Log::error("Error fetching outgoing documents: " . $e->getMessage());
            return redirect()->route('document.outgoing')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    public function rejected()
    {
        try {
            $query = DocumentTracking::where('status', 'rejected')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            // Check if no records found
            if ($documentTrackings->isEmpty()) {
                return redirect()->route('document.rejected')->with('info', 'No rejected documents found.');
            }

            return view('document.rejected', compact('documentTrackings'));
        } catch (\Exception $e) {
            \Log::error("Error fetching rejected documents: " . $e->getMessage());
            return redirect()->route('document.rejected')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    public function tracked()
    {
        try {
            $query = DocumentTracking::where('status', 'rejected')->with('user', 'documentDetail');

            if (auth()->user()->is_admin == 0) {
                $query->where('office_division', auth()->user()->office_division);
            }

            $documentTrackings = $query->get();

            if ($documentTrackings->isEmpty()) {
                $documentTrackings = collect();
            }

            return view('document.tracked', compact('documentTrackings'));
        } catch (\Exception $e) {
            \Log::error("Error fetching tracked documents: " . $e->getMessage());
            return redirect()->route('document.tracked')->with('error', 'An error occurred while fetching the documents.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $documents = DocumentDetail::all();
    //     return view('document.create',compact('documents'));
    // }

    // public function create($type)
    // {
    //     // $val = substr('MO-RO8-03-2023-001',15,18);
    //     // dd($val);
    //     // $document_code = $this->generateDocumentNumber();
    //     // $documents = DocumentDetail::where('document_code', 'LIKE', '%'.$type.'%')->get();
    //     $documents = DocumentDetail::where('type',$type)
    //     ->get();
    //     return view('document.create',compact('documents','type'));
    // }

    public function create()
    {
        // $val = substr('MO-RO8-03-2023-001',15,18);
        // dd($val);
        // $document_code = $this->generateDocumentNumber();
        // $documents = DocumentDetail::where('document_code', 'LIKE', '%'.$type.'%')->get();
        $documents = DocumentDetail::all();
        return view('document.create', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (! method_exists($this, 'generateDocumentNumber')) {
                throw new \Exception('generateDocumentNumber method is missing.');
            }

            if (! empty($request->document_code)) {
                if ($this->documentNumberExists($request->document_code)) {
                    return response()->json(["message" => "Document code already exist"], 422);
                }
                $document_code = $request->document_code;
            } else {
                $document_code = $this->generateDocumentNumber();
            }

            DB::transaction(function () use ($request, $document_code) {
                $user = auth()->user();
                if (! $user) {
                    throw new \Exception('User is not authenticated.');
                }

                $documentDetail = DocumentDetail::create([
                    'user_id'       => $user->id,
                    'document_code' => $document_code,
                    'type'          => $request->type,
                    'origin'        => $request->origin,
                    'subject'       => $request->subject,
                    'status'        => $request->status,
                    'status_name'   => $request->status_name,
                    'forward_to'    => $request->forward_to,
                    'remarks'       => $request->remarks,
                ]);

                DocumentTracking::create([
                    'user_id'            => $user->id,
                    'document_detail_id' => $documentDetail->id,
                    'office_division'    => $documentDetail->forward_to,
                ]);

                DocumentTrace::create([
                    'user_id'            => $user->id,
                    'document_detail_id' => $documentDetail->id,
                ]);
            });

            Alert::success('Success', 'Document created successfully!');
            return redirect()->route('document.received');
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Check if the document code already exists.
     */

    /**
     * Generate code
     */
    public function generateDocumentNumber()
    {
        $datePart = date('Ymd');
        do {
            $randomPart     = mt_rand(1000, 9999);
            $documentNumber = $datePart . $randomPart;
        } while ($this->documentNumberExists($documentNumber));

        return $documentNumber;
    }

    public function documentNumberExists($code)
    {
        return DocumentDetail::where('document_code', $code)->exists();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                //from incoming.blade.php
                if ($request->status === "outgoing") {
                    $documentTracking                  = DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id         = auth()->user()->id;
                    $documentTracking->status          = $request->status;
                    $documentTracking->office_division = $request->office_division;
                    $documentTracking->save();

                    Outgoing::create([
                        'document_detail_id' => $documentTracking->id,
                        'user_id'            => auth()->user()->id,
                        'office_division'    => $request->office_division,
                    ]);

                } elseif ($request->status === "released") {
                    $documentTracking         = DocumentTracking::FindOrFail($id);
                    $documentTracking->status = $request->status;
                    $documentTracking->save();

                    DocumentTrace::create([
                        'user_id'            => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                    ]);

                    ReceivedHistory::create([
                        'user_id'            => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                    ]);

                } elseif ($request->status === "rejected") {
                    $documentTracking          = DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id = auth()->user()->id;
                    $documentTracking->status  = $request->status;
                    $documentTracking->save();
                } else {

                }
            });
        } catch (\Exception $e) {
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        Alert::success('Success', 'Successfully updated...');
        return redirect()->back();
    }

    public function updateEdit(Request $request, string $id)
    {
        // dd($request->all());
        try {
            DB::transaction(function () use ($request, $id) {
                $documentDetail          = DocumentDetail::find($id);
                $documentDetail->type    = $request->type;
                $documentDetail->status    = $request->status;
                $documentDetail->origin  = $request->origin;
                $documentDetail->forward_to    = $request->forward_to;
                $documentDetail->status_name = $request->status_name;
                $documentDetail->save();
            });
        } catch (\Exception $e) {
            Alert::error('Ooppss', 'Please try again...');
            return redirect()->back();
        }

        Alert::success('Success', 'Successfully updated...');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
