<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:11',
        ]);

        // try {
            DB::beginTransaction();

            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            if($request->proposal_image != null){
                $proLogo = Setting::where('name', 'proposal-logo')->first();
                if($proLogo){
                    $oldSetting = Setting::find($proLogo->id);
                    @unlink($proLogo->value);
                    $baseImage      = $request->proposal_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $proLogo->name . "-" . time() . ".png";
                    $location   = 'uploads/proposal/';
                    if (!file_exists($location)) {
                        mkdir('uploads/proposal/');
                    }
                    Image::make($image)->save($location . $image_name)->resize(300, 300);
                    $oldSetting->value = $location . $image_name;
                    $oldSetting->save();
                }else{
                    $setting = new Setting();
                    $setting->name = 'proposal-logo';
                    $baseImage      = $request->proposal_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = "proposal-logo-" . time() . ".png";
                    $location   = 'uploads/proposal/';
                    if (!file_exists($location)) {
                        mkdir('uploads/proposal/');
                    }
                    Image::make($image)->save($location . $image_name)->resize(300, 300);
                    $setting->value = $location . $image_name;
                    $setting->save();
                }
            }

            if($request->app_image != null){
                $proLogo = Setting::where('name', 'app-logo')->first();
                if($proLogo){
                    $oldSetting = Setting::find($proLogo->id);
                    @unlink($proLogo->value);
                    $baseImage      = $request->app_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = $proLogo->name . "-" . time() . ".png";
                    $location   = 'uploads/app/';
                    if (!file_exists($location)) {
                        mkdir('uploads/app/');
                    }
                    Image::make($image)->save($location . $image_name)->resize(300, 300);
                    $oldSetting->value = $location . $image_name;
                    $oldSetting->save();
                }else{
                    $setting = new Setting();
                    $setting->name = 'app-logo';
                    $baseImage      = $request->app_image;
                    $base64_str = substr($baseImage, strpos($baseImage, ",") + 1);
                    $image      = base64_decode($base64_str);
                    $image_name   = "app-logo-" . time() . ".png";
                    $location   = 'uploads/app/';
                    if (!file_exists($location)) {
                        mkdir('uploads/app/');
                    }
                    Image::make($image)->save($location . $image_name)->resize(300, 300);
                    $setting->value = $location . $image_name;
                    $setting->save();
                }
            }

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');

        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return back()->with('error', $th->getMessage());
        // }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Password Changed Successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
