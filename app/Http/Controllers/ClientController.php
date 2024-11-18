<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Libraries\CommonFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

use App\Mail\ClientVerifyMail;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index', 'getClients']]);
        $this->middleware('permission:client-create', ['only' => ['create']]);
        $this->middleware('permission:client-edit', ['only' => ['getClient', 'updateClient']]);
        $this->middleware('permission:client-delete', ['only' => ['deleteClient']]);
    }


    public function index()
    {
        return view('client.index');
    }

    public function getClients(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        try {
            $list = Client::where('user_id', Auth()->user()->id)->with('userInfo')->get();
            return DataTables::of($list)
                ->editColumn('status', function ($list) {
                    return CommonFunction::getStatus($list->userInfo->status);
                })
                ->editColumn('enlisted', function ($list) {
                    return CommonFunction::getEnlistment($list->userInfo->email_verified_at);
                })
                ->editColumn('name', function ($list) {
                    return $list->userInfo->first_name." ".$list->userInfo->last_name;
                })
                ->editColumn('email', function ($list) {
                    return $list->userInfo->email;
                })
                ->editColumn('phone', function ($list) {
                    return $list->userInfo->mobile_number;
                })
                ->addColumn('action', function ($list) {
                    // return "";
                    return '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="text-primary ti ti-dots-vertical"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0 p-0">
                        <span class="dropdown-item py-2 text-center">Details</span>
                        <div class="dropdown-divider m-0"></div>
                        <li><a onClick="editClient(this)" data-id="'.$list->userInfo->id.'" class="dropdown-item py-2 text-primary"><i class="fas fa-pen pr-2"></i> Update</a></li>
                        <li><a onClick="deleteClient(this)" data-id="'.$list->userInfo->id.'" class="dropdown-item py-2 text-danger"><i class="fas fa-trash pr-2"></i> Delete</a></li>
                    </ul>
                    </div>';
                })
                ->addIndexColumn()
                ->rawColumns(['status', 'enlisted', 'name', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return Redirect::back();
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email',
            'mobile_number' => 'required|numeric|digits:11',
            'status'       =>  'required|numeric|in:0,1',
        ]);
        $newUser = new User();
        $newUser->first_name = $request->first_name;
        $newUser->last_name = $request->last_name;
        $newUser->email = $request->email;
        $newUser->mobile_number = $request->mobile_number;
        $newUser->role_id = 3;
        $newUser->password = Hash::make($request->first_name.'@'.$request->mobile_number);
        $newUser->status = $request->status;
        if($newUser->save()){
            DB::table('model_has_roles')->where('model_id',$newUser->id)->delete();
            $newUser->assignRole($newUser->role_id);

            $newClient = new Client();
            $newClient->user_id = Auth()->user()->id;
            $newClient->client_id = $newUser->id;
            if($newClient->save()){
                $verify = $this->verifyClient($newClient)->getData();
                // dd($verify->status);
                if($verify->status == 'success'){
                    return redirect()->back()->with('success', 'Create client and email send successful');
                }else if($verify->status == 'error'){
                    return redirect()->back()->with('success', 'Create client successful, '.$verify->message);
                }else{
                    return redirect()->back()->with('success', 'Create client successful and email send failed');
                }
            }else{
                $deleteUser = User::find($newUser->id);
                $deleteUser->delete();
                return redirect()->back()->with('error', 'Failed to save client.');
            }
        }else{
            return redirect()->back()->with('error', 'Failed to save client.');
        }

    }

    public function verifyClient($client)
    {
        $clientUser = User::find($client->client_id);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $clientUser->id, 'hash' => sha1($clientUser->getEmailForVerification())]
        );

        $data = [
            "subject" => "Client account verification",
            "app_name" => getSetting('app-name'),
            "verify_url" => $verificationUrl,
            "client" => $clientUser,
        ];

        try {
            Mail::to($clientUser->email)->send(new ClientVerifyMail($data));
            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send verify email. ' . $e->getMessage(),
            ]);
        }
    }

    public function getClient(Request $request)
    {
        return User::find($request->id);
    }

    public function updateClient(Request $request)
    {
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required',
            'mobile_number' => 'required|numeric|digits:11',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        $user = User::find($request->id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile_number = $request->mobile_number;
        $user->role_id = 3;
        if($request->password){
            $request->validate([
                'password' => 'nullable|min:8|confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;

        if($user->save()){
            return redirect()->back()->with('success', 'Update client successful.');
        }else{
            return redirect()->back()->with('error', 'Failed to update client.');
        }
    }

    public function deleteClient(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:clients,client_id',
        ]);
        DB::beginTransaction();
        try {
            $client = Client::where('client_id', $request->id)->firstOrFail();
            $user = User::findOrFail($client->client_id);

            // Attempt to delete the user and client
            $user->delete();
            $client->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Client deleted successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete client.'], 500);
        }

    }


}
