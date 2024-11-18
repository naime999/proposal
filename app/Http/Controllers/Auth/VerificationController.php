<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {

        $timestamp = $request->expires;
        $expires = Carbon::createFromTimestamp($timestamp);

        if($expires->isFuture()){
            $clientUser = User::find($request->id);
            if($clientUser->email_verified_at == null){
                return view('auth.verify', ['clientUser' => $clientUser]);
            }else{
                return redirect()->route('login')->with('verify_error', 'You already verified, Please login your client account');
            }
        }else{
            return redirect()->route('login')->with('verify_error', 'Your verify session is time out');
        }
    }

    public function verifySave(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8', // At least 8 characters
                'regex:/[A-Za-z]/', // At least one letter
                'regex:/[0-9]/',    // At least one number
                'regex:/[@$!%*?&#]/', // At least one special character
            ],
            'confirm_password' => 'required|same:password', // Must match the password field
        ]);

        $clientUser = User::find($request->id);
        if($clientUser->email_verified_at == null){
            $clientUser->password = Hash::make($request->password);
            $clientUser->email_verified_at = Carbon::now();
            if($clientUser->save()){
                return redirect()->route('login')->with('verify_success', 'Your password save successful, Please login your account');
            }else{
                return redirect()->back()->with('verify_error', 'Your password save failed, Please try again');
            }
        }else{
            return redirect()->route('login')->with('verify_error', 'You already verified, Please login your client account');
        }

    }


}
