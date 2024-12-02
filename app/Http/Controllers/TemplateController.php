<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\CategoryProposal;
use App\Models\Proposal;
use App\Models\Client;
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

class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'getData']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'addSection']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'updateSection', 'updateData']]);
        $this->middleware('permission:category-delete', ['only' => ['delete', 'deleteSection']]);
    }

    public function index()
    {
        $clients = Client::where('user_id', Auth()->user()->id)->with('userInfo')->get();
        $categories = Category::with('relations')->get();
        return view('templates.index', compact('clients', 'categories'));
    }

    public function getTemplates(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        $data = new \stdClass();
        $data->proposals = [];

        if($request->cat_id == 0){
            $catProposal = CategoryProposal::with('proposal', 'category')->get();
        }else{
            $catProposal = CategoryProposal::where('category_id', $request->cat_id)->with('proposal', 'category')->get();
        }


        $data->cat_proposal = $catProposal;
        $proposalIds = [];
        foreach ($catProposal as $item) {
            $proposal = $item->proposal;

            if (!in_array($proposal->id, $proposalIds)) {
                $data->proposals[] = $proposal;
                $proposalIds[] = $proposal->id;
            }
        }
        return $data;
    }

    public function duplicate(Request $request)
    {
        // dd($request->all());

        if(Auth()->user()->role_id != 1){
            return redirect()->back()->with('error', 'No permission to create proposal.');
        }

        $this->validate($request, [
            'client_id' => 'required',
            'title' => 'required|max:500|unique:proposals,title',
        ],[
            "client_id.required" => "The client field is required",
            'title.unique' => 'The title must be unique',
        ]);

        $duplicateProposal = Proposal::where('id', $request->id)->with('sections')->first();



        $newProposal = new Proposal();

        $creator = Auth()->user();
        $newProposal->user_id = $creator->id;
        $newProposal->client_id = $request->client_id;
        $newProposal->title = $request->title;
        $newProposal->slug = Str::slug($request->title, '-');
        if($duplicateProposal->cover != null){
            $new_name_cover = "cover-" . time() . ".png";
            $new_location_cover = 'uploads/proposal/';
            copy($duplicateProposal->cover, $new_location_cover . $new_name_cover);
            $newProposal->cover = $new_location_cover . $new_name_cover;
        }
        if($duplicateProposal->logo != null){
            $new_name_logo = "logo-" . time() . ".png";
            $new_location_logo = 'uploads/proposal/';
            copy($duplicateProposal->logo, $new_location_logo . $new_name_logo);
            $newProposal->logo = $new_location_logo . $new_name_logo;
        }
        if($duplicateProposal){
            if($newProposal->save()){
                foreach($duplicateProposal->sections as $section){
                    // Create a new section for the new proposal
                    $newSection = new ProposalSection();
                    $newSection->proposal_id = $newProposal->id;
                    $newSection->type = $section->type;
                    $newSection->title = $section->title;
                    $newSection->sub_title = $section->sub_title;
                    $newSection->slug = Str::slug($section->title, '-');
                    $newSection->description = $section->description;
                    $newSection->sort = $section->sort;
                    $newSection->status = $section->status;
                    $newSection->save();
                }
                return redirect()->route('users.proposal.show', ['slug' => $newProposal->slug]);
            }else{
                return redirect()->back()->with('error', 'Failed to copy this proposal.');
            }
        }else{
            return redirect()->back()->with('error', 'Failed to copy this proposal, Please select a proposal');
        }
    }

}
