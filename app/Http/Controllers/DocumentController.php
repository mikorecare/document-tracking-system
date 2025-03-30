<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentDetail;
use App\Models\DocumentTrace;
use App\Models\DocumentTracking;
use App\Models\Outgoing;
use App\Models\ReceivedHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Node\Block\Document;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Console\Output\Output;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    function find(Request $request){
    //     $request->validate([
    //       'query'=>'required|min:2'
    //    ]);


        // dd($request->input('query'));

      try {
            $search_text = $request->input('query');

            $documentDetail = DocumentDetail::where('document_code',$search_text)->first();

            if($documentDetail){
                $documentTraces = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->get();
                $documentLatest = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->latest()->first();
                $data = $documentLatest->user->office_division; 
                // throw new Exception('Something went wrong');
                $documentTracking = DocumentTracking::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->first();
                if($data == $documentTracking->office_division && $documentTracking->status == "incoming"){
                    $documentTracking =null;
                }

            }else {
                Alert::error('oppss', 'No record found...');
                return view('document.tracked');
            }
      } catch (\Exception $e) {
            Alert::error('Something went wrong', 'Please try again...');
            return view('document.tracked');
      }


       return view('document.tracked',compact('documentTraces','documentTracking','documentDetail'));
    }

    function find2(Request $request){
        //     $request->validate([
        //       'query'=>'required|min:2'
        //    ]);
    
    
            // dd($request->input('query'));
    
          try {
                $search_text = $request->input('query');
    
                $documentDetail = DocumentDetail::where('document_code',$search_text)->first();
    
                if($documentDetail){
                    $documentTraces = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->get();
                    $documentLatest = DocumentTrace::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->latest()->first();
                    $data = $documentLatest->user->office_division; 
                    // throw new Exception('Something went wrong');
                    $documentTracking = DocumentTracking::where('document_detail_id',$documentDetail->id)->with('user','documentDetail')->first();
                    if($data == $documentTracking->office_division && $documentTracking->status == "incoming"){
                        $documentTracking =null;
                    }
                }else {
                    Alert::error('oppss', 'No record found...');
                    return view('tracked');
                }
          } catch (\Exception $e) {
                Alert::error('Something went wrong', 'Please try again...');
                return view('tracked');
          }
    
    
           return view('tracked',compact('documentTraces','documentTracking','documentDetail'));
        }

    public function dts(){
        return view('tracked');
    }

    public function createPDF(string $id){
        $data = DocumentDetail::where('id',$id)->first();

        return view('document.pdf_view',compact('data'));
        // $pdf = Pdf::loadView('document.pdf_view', compact('data'))->setPaper('a4', 'landscape')->setWarnings(false);

        // return $pdf->stream();
    }

    public function allDocuments()
    {
        $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.received', compact('documentTrackings'));
    }
    
    public function received()
    {
        $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.received', compact('documentTrackings'));
    }
    
    public function incoming()
    {
        $query = DocumentTracking::where('status', 'received')->with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.incoming', compact('documentTrackings'));
    }
    
    public function receivedHistory()
    {
        $query = ReceivedHistory::with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $receivedHistories = $query->get();
        return view('document.received_histories', compact('receivedHistories'));
    }
    
    public function outgoing()
    {
        $query = Outgoing::with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.outgoing', compact('documentTrackings'));
    }
    
    public function rejected()
    {
        $query = DocumentTracking::where('status', 'rejected')->with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.rejected', compact('documentTrackings'));
    }
    
    public function tracked()
    {
        $query = DocumentTracking::where('status', 'rejected')->with('user', 'documentDetail');
    
        if (auth()->user()->is_admin == 0) {
            $query->where('office_division', auth()->user()->office_division);
        }
    
        $documentTrackings = $query->get();
        return view('document.tracked', compact('documentTrackings'));
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
        return view('document.create',compact('documents'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!method_exists($this, 'generateDocumentNumber')) {
                throw new \Exception('generateDocumentNumber method is missing.');
            }
    
            if (!empty($request->document_code)) {
                if ($this->documentNumberExists($request->document_code)) {
                    return response()->json(["message" => "Document code already exist"], 422);
                }
                $document_code = $request->document_code;
            } else {
                $document_code = $this->generateDocumentNumber();
            }
    
            DB::transaction(function () use ($request, $document_code) {
                $user = auth()->user();
                if (!$user) {
                    throw new \Exception('User is not authenticated.');
                }
    
                $documentDetail = DocumentDetail::create([
                    'user_id' => $user->id,
                    'document_code' => $document_code,
                    'type' => $request->type,
                    'origin' => $request->origin,
                    'subject' => $request->subject,
                    'status' => $request->status,
                    'status_name'=> $request->status_name,
                    'forward_to' => $request->forward_to,
                    'remarks' => $request->remarks
                ]);
    
                DocumentTracking::create([
                    'user_id' => $user->id,
                    'document_detail_id' => $documentDetail->id,
                    'office_division' => $documentDetail->forward_to,
                ]);
    
                DocumentTrace::create([
                    'user_id' => $user->id,
                    'document_detail_id' => $documentDetail->id,
                ]);
    
                ReceivedHistory::create([
                    'user_id' => $user->id,
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
     function generateDocumentNumber(){
        $number = mt_rand(100000, 999999);

        if ($this->documentNumberExists($number)) {
            return $this->generateRegistrationNumber();
        } 

        return $number;
    }

    function documentNumberExists($code){
        return DocumentDetail::where('document_code',$code)->exists();
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
            DB::transaction(function () use ($request, $id){
                //from incoming.blade.php
                if($request->status === "outgoing"){
                    $documentTracking =  DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id = auth()->user()->id;
                    $documentTracking->status = $request->status;
                    $documentTracking->office_division = $request->office_division;
                    $documentTracking->save();
        
                    Outgoing::create([
                        'document_detail_id' => $documentTracking->id,
                        'user_id' => auth()->user()->id,
                        'office_division' => $request->office_division,
                    ]);
        
                }elseif($request->status === "released"){
                    $documentTracking =  DocumentTracking::FindOrFail($id);
                    $documentTracking->status = $request->status;
                    $documentTracking->save();

                    DocumentTrace::create([
                        'user_id' => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                    ]);

                    ReceivedHistory::create([
                        'user_id' => auth()->user()->id,
                        'document_detail_id' => $documentTracking->id,
                    ]);
                    
                }elseif($request->status === "rejected"){
                    $documentTracking =  DocumentTracking::FindOrFail($id);
                    $documentTracking->user_id = auth()->user()->id;
                    $documentTracking->status = $request->status;
                    $documentTracking->save();
                }
            });
        } catch (\Exception $e){
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
            DB::transaction(function () use ($request, $id){
                $documentDetail = DocumentDetail::find($id);
                $documentDetail->type = $request->type;
                $documentDetail->origin = $request->origin;
                $documentDetail->subject = $request->subject;
                $documentDetail->save();
            });
        } catch (\Exception $e){
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