<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Proposal;
use App\Models\ProposalSection;
use App\Models\ProposalSignature;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create','store', 'updateStatus']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }


    /**
     * List User
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function index()
    {
        $clients = User::where('role_id',3)->with('roles')->get();
        return view('proposals.index', compact('clients'));
    }

    public function create(Request $request)
    {
        $proposal = Proposal::where('id', 1)->with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->first();
        return view('proposals.add', compact('proposal'));
    }

    public function loadData(Request $request)
    {
        $proposal = Proposal::where('id', $request->id)->with('sections', 'adminSignature', 'clientSignature', 'client', 'creator')->first();
        return $proposal;
    }

    public function getSection(Request $request)
    {
        $section = ProposalSection::where('id', $request->id)->with('proposal')->first();
        return $section;
    }

    public function addSection(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:500',
            'sub_title' => 'required|max:500',
            'description' => 'required',
            'status' => 'required',
        ]);

        $oldSection = ProposalSection::where('id', $request->section_id)->with('proposal')->first();
        $newSection = new ProposalSection();
        $newSection->proposal_id = $oldSection->proposal_id;
        $newSection->type = 1;
        $newSection->title = $request->title;
        $newSection->sub_title = $request->sub_title;
        $newSection->slug = Str::slug($request->title, '-');
        $newSection->description = $request->description;
        $newSection->sort = ($oldSection->sort + 1);
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

    /**
     * Store User
     * @param Request $request
     * @return View Users
     * @author Shani Singh
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user = User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
                'password'      => Hash::make($request->first_name.'@'.$request->mobile_number)
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     * @author Shani Singh
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success','User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit User
     * @param Integer $user
     * @return Collection $user
     * @author Shani Singh
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit')->with([
            'roles' => $roles,
            'user'  => $user
        ]);
    }

    /**
     * Update User
     * @param Request $request, User $user
     * @return View Users
     * @author Shani Singh
     */
    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email,'.$user->id.',id',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user_updated = User::whereId($user->id)->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();

            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete User
     * @param User $user
     * @return Index Users
     * @author Shani Singh
     */
    public function delete(User $user)
    {
        DB::beginTransaction();
        try {
            // Delete User
            User::whereId($user->id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Users
     * @param Null
     * @return View File
     */
    public function importUsers()
    {
        return view('users.import');
    }

    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);

        return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

}
