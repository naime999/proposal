<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Proposal;
use App\Models\ProposalSection;
use App\Models\ProposalSignature;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Libraries\CommonFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Mail\ExampleMail;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:proposal-list|proposal-create|proposal-edit|proposal-delete', ['only' => ['index', 'getData']]);
        $this->middleware('permission:proposal-show', ['only' => ['showProposal', 'loadData', 'getSection', 'getSignature']]);
        $this->middleware('permission:proposal-create', ['only' => ['create', 'addSection']]);
        $this->middleware('permission:proposal-edit', ['only' => ['edit', 'updateSection', 'updateData']]);
        $this->middleware('permission:proposal-delete', ['only' => ['delete', 'deleteSection']]);
    }

    public function index()
    {
        $clients = Client::where('user_id', Auth()->user()->id)->with('userInfo')->get();
        // $clients = User::where('role_id',3)->whereNotNull('email_verified_at')->with('roles')->get();
        return view('proposals.index', compact('clients'));
    }

    public function getData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        try {
            if(Auth()->user()->role_id == 3){
                $list = Proposal::with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->where('client_id', Auth()->user()->id)->get();
            }else{
                $list = Proposal::with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->where('user_id', Auth()->user()->id)->get();
            }
            return DataTables::of($list)
                ->editColumn('status', function ($list) {
                    return CommonFunction::getProposalStatus($list->status);
                })
                ->editColumn('client', function ($list) {
                    return $list->client->first_name." ".$list->client->last_name;
                })
                ->addColumn('action', function ($list) {
                    // return "";
                    return '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="text-primary ti ti-dots-vertical"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0 p-0">
                        <li><a href="javascript:;" class="dropdown-item py-2"><i class="fas fa-list pr-2"></i> Details</a></li>
                        <div class="dropdown-divider m-0"></div>
                        <li><a href="'.route('users.proposal.show', $list->slug).'" class="dropdown-item py-2 text-primary delete-record"><i class="fas fa-eye pr-2"></i> View</a></li>
                    </ul>
                    </div>';
                })
                ->addIndexColumn()
                ->rawColumns(['status', 'image', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            // Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1001]');
            return Redirect::back();
        }

    }

    public function create(Request $request)
    {
        // dd($request->all());

        if(Auth()->user()->role_id == 3){
            return redirect()->back()->with('error', 'No permission to create proposal.');
        }

        $this->validate($request, [
            'client_id' => 'required',
            'title' => 'required|max:500',
        ],[
            "client_id.required" => "The client field is required",
        ]);

        $newProposal = new Proposal();

        $creator = Auth()->user();
        $newProposal->user_id = $creator->id;
        $newProposal->client_id = $request->client_id;
        $newProposal->title = $request->title;
        $newProposal->slug = Str::slug($request->title, '-');

        if($newProposal->save()){
            return redirect()->route('users.proposal.show', ['slug' => $newProposal->slug]);
        }else{
            return redirect()->back()->with('error', 'Failed to save proposal.');
        }
    }

    public function showProposal(Request $request)
    {
        $proposal = Proposal::where('slug', $request->slug)->with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->first();
        return view('proposals.add', compact('proposal'));
    }

    public function loadData(Request $request)
    {
        $proposal = Proposal::where('slug', $request->slug)->with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->first();
        return $proposal;
    }

    public function getSection(Request $request)
    {
        $section = ProposalSection::where('id', $request->id)->with('proposal')->first();
        return $section;
    }

    public function addSection(Request $request)
    {
        if(Auth()->user()->role_id == 3){
            return response()->json([
                'status' => 'error',
                'message' => 'No permission to create section.'
            ]);
        }

        $this->validate($request, [
            'title' => 'required|max:500',
            'sub_title' => 'required|max:500',
            'description' => 'required',
            'status' => 'required',
        ]);

        $oldSection = ProposalSection::where('id', $request->section_id)->with('proposal')->first();
        $newSection = new ProposalSection();
        $newSection->proposal_id = $oldSection ? $oldSection->proposal_id : $request->proposal_id;
        $newSection->type = 1;
        $newSection->title = $request->title;
        $newSection->sub_title = $request->sub_title;
        $newSection->slug = Str::slug($request->title, '-');
        $newSection->description = $request->description;
        $newSection->sort = $oldSection ? ($oldSection->sort + 1) : 1;
        $newSection->status = $request->status;
        // return $newSection;

        if($newSection->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'New section add successfully',
                'data' => $newSection
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'New section add failed'
            ]);
        }
    }

    public function updateSection(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:500',
            'sub_title' => 'required|max:500',
            'description' => 'required',
            'status' => 'required',
        ]);
        $updateSection =  ProposalSection::find($request->section_id);
        $updateSection->title = $request->title;
        $updateSection->sub_title = $request->sub_title;
        $updateSection->slug = Str::slug($request->title, '-');
        $updateSection->description = $request->description;
        $updateSection->status = $request->status;

        if($updateSection->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Section Update successfully',
                'data' => $updateSection
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Section Update failed'
            ]);
        }
    }

    public function deleteSection(Request $request)
    {

        $deleteSection =  ProposalSection::find($request->id);
        if($deleteSection->delete()){
            return response()->json([
                'status' => 'success',
                'message' => 'Section successfully deleted',
                'data' => $deleteSection
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Section delete failed'
            ]);
        }
    }

    public function updateData(Request $request)
    {
        // return $request->all();
        $proposal = Proposal::find($request->id);
        $proposal->title = $request->title;
        $proposal->slug = Str::slug($request->title, '-');
        if($proposal->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Proposal updated successfully',
                'data' => $proposal
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Proposal updated failed'
            ]);
        }
    }

    public function getSignature(Request $request)
    {
        // return $request->all();
        $proposalSignature =  ProposalSignature::where('proposal_id', $request->proposal_id)->where('type', $request->user_type)->first();

        if ($proposalSignature) {
            return response()->json($proposalSignature, 200);
        }else{
            return response()->json([
                'message' => 'Signature not found for the provided proposal ID and user type.'
            ], 404);
        }

    }

    public function saveSignature(Request $request)
    {
        // return $request->all();
        $proposalSignature = ProposalSignature::where('proposal_id', $request->proposal_id)->where('type', $request->user_type)->first();

        if($proposalSignature){
            if($request->sig_type == 1){
                $this->validate($request, [
                    'title' => 'required|max:500',
                ]);
                if($request->title != null){
                    $proposalSignature->title = $request->title;
                }
            } else if($request->sig_type == 2){
                $this->validate($request, [
                    'upload_image' => 'required',
                ]);
                @unlink($proposalSignature->image);
                if ($request->upload_image != null) {
                    $baseImage      = $request->upload_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $request->proposal_id . "-" . time() . ".png";
                    $location   = 'uploads/signatures/';
                    if (!file_exists($location)) {
                        mkdir('uploads/signatures/');
                    }
                    // $resizedImage = Image::make($image)->resize(300, 300);
                    Image::make($image)->save($location . $image_name);
                    $proposalSignature->image = $location . $image_name;
                }
            } else if($request->sig_type == 3){
                $this->validate($request, [
                    'drow_image' => 'required',
                ]);
                @unlink($proposalSignature->image);
                if ($request->drow_image != null) {
                    $baseImage      = $request->drow_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $request->proposal_id . "-" . time() . ".png";
                    $location   = 'uploads/signatures/';
                    if (!file_exists($location)) {
                        mkdir('uploads/signatures/');
                    }
                    // $resizedImage = Image::make($image)->resize(300, 300);
                    Image::make($image)->save($location . $image_name);
                    $proposalSignature->image = $location . $image_name;
                }
            }

            if($proposalSignature->save()){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Proposal signature updated successfully',
                    'data' => $proposalSignature
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Proposal signature updated failed'
                ]);
            }
        }else{
            $newSignature = new ProposalSignature();
            $newSignature->proposal_id = $request->proposal_id;
            $newSignature->type = $request->user_type;
            if($request->sig_type == 1){
                $this->validate($request, [
                    'title' => 'required|max:500',
                ]);
                if($request->title != null){
                    $newSignature->title = $request->title;
                }
            } else if($request->sig_type == 2){
                $this->validate($request, [
                    'upload_image' => 'required',
                ]);
                if ($request->upload_image != null) {
                    $baseImage      = $request->upload_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $request->proposal_id . "-" . time() . ".png";
                    $location   = 'uploads/signatures/';
                    if (!file_exists($location)) {
                        mkdir('uploads/signatures/');
                    }
                    // $resizedImage = Image::make($image)->resize(300, 300);
                    Image::make($image)->save($location . $image_name);
                    $newSignature->image = $location . $image_name;
                }
            } else if($request->sig_type == 3){
                $this->validate($request, [
                    'drow_image' => 'required',
                ]);
                if ($request->drow_image != null) {
                    $baseImage      = $request->drow_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $request->proposal_id . "-" . time() . ".png";
                    $location   = 'uploads/signatures/';
                    if (!file_exists($location)) {
                        mkdir('uploads/signatures/');
                    }
                    // $resizedImage = Image::make($image)->resize(300, 300);
                    Image::make($image)->save($location . $image_name);
                    $newSignature->image = $location . $image_name;
                }
            }

            if($newSignature->save()){
                if($newSignature->type == 2){
                    $proposal = Proposal::find($newSignature->proposal_id);
                    $proposal->status = 2;
                    $proposal->save();
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Proposal signature add successfully',
                    'data' => $newSignature
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Proposal signature add failed'
                ]);
            }
        }

    }


    public function sendProposal(Request $request)
    {
        $proposal = Proposal::where('id',$request->id)->with('client')->first();

        // Check if the proposal exists
        if (!$proposal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proposal not found.',
            ]);
        }

        // Check if client email exists
        if (!$proposal->client || !$proposal->client->email) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client email not found.',
            ]);
        }

        $data = [
            "subject" => $proposal->title,
            "app_name" => getSetting('app-name'),
            "proposal" => $proposal,
        ];


        try {
            Mail::to($proposal->client->email)->send(new ExampleMail($data));
            return response()->json([
                'status' => 'success',
                'message' => 'Proposal email sending successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send proposal email. ' . $e->getMessage(),
            ]);
        }
    }

    // public function export()
    // {
    //     return Excel::download(new UsersExport, 'users.xlsx');
    // }

}
