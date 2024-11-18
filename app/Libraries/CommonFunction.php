<?php

namespace App\Libraries;

use App\EmailQueue;
use App\Templates;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CommonFunction
{

    public static function getProjectRootDirectory()
    {
        return base_path();
    }
    public static function getImageFromURL($db_path, $local_path=null, $id=null, $width='120px', $height ='100px')
    {
        $file_path = (string)($local_path.$db_path);
        if (is_file($file_path)) {
            return '<a href="'. asset($file_path) .'" target="_blank"><img class="img-thumbnail" src="'. asset($file_path) .'" alt="Something" style="width: '.$width.'; height: '.$height.';" id="'.$id.'" /></a>';
         } else {
            return "<img class='img-thumbnail' src='" . asset('assets/backend/img/no_image_found.png') . "' alt='Image not found' style='width: $width; height: $height;' id='$id'>";
        }
    }

    public static function getcoverImageFromURL($db_path, $local_path=null, $id=null, $width='250px', $height ='150px')
    {
        $file_path = (string)($local_path.$db_path);
        if (is_file($file_path)) {
            return '<a href="'. asset($file_path) .'" target="_blank"><img class="img-thumbnail" src="'. asset($file_path) .'" alt="Something" style="width: '.$width.'; height: '.$height.';" id="'.$id.'" /></a>';
         } else {
            return "<img class='img-thumbnail' src='" . asset('assets/backend/img/no_image_found.png') . "' alt='Image not found' style='width: $width; height: $height;' id='$id'>";
        }
    }

    public static function getbedImageFromURL($db_path, $local_path=null, $id=null, $width='100px', $height ='100px')
    {
        $file_path = (string)($local_path.$db_path);
        if (is_file($file_path)) {
            return '<img class="img-thumbnail '.$id.'" src="'. asset($file_path) .'" alt="Something" style="width: '.$width.'; height: '.$height.';" />';
         } else {
            return "<img class='img-thumbnail ".$id."' src='" . asset('assets/admin/img/no_image_found.png') . "' alt='Image not found' style='width: $width; height: $height;'>";
        }
    }


    public static function imageDelete($file_path)
    {
        if(file_exists($file_path)){
            @unlink($file_path);
        }
    }

    public static function getEnlistment($verify) {
        if ($verify != null) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Enlisted';
        } else {
            $class = 'badge rounded-pill px-3 py-1 bg-danger bg-glow';
            $status = 'Not listed';
        }
        return '<span class="' . $class . '">' . $status . '</span>';
    }
    public static function getStatus($status) {
        if (!empty($status) && $status == 1) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Active';
        } else if (!empty($status) && $status == 2) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Pending';
        } else if (!empty($status) && $status == 3) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Running';
        }else if (!empty($status) && $status == 4) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Complete';
        }else if (!empty($status) && $status == 5) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Success';
        }else if (!empty($status) && $status == 6) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Field';
        } else{
            $class = 'badge rounded-pill px-3 py-1 bg-danger bg-glow';
            $status = 'Inactive';
        }
        return '<span class="' . $class . '">' . $status . '</span>';
    }

    public static function getProposalStatus($status) {
        if (!empty($status) && $status == 1) {
            $class = 'badge rounded-pill px-3 py-1 bg-success bg-glow';
            $status = 'Send';
        } else if (!empty($status) && $status == 2) {
            $class = 'badge rounded-pill px-3 py-1 bg-primary bg-glow';
            $status = 'Signinged';
        } else{
            $class = 'badge rounded-pill px-3 py-1 bg-warning bg-glow';
            $status = 'Draft';
        }
        return '<span class="' . $class . '">' . $status . '</span>';
    }

    public static function getType($type) {
        if (!empty($type) && $type == 1) {
            $class = 'badge rounded-pill bg-success bg-glow';
            $type = 'ND Resources';
        } else {
            $class = 'badge rounded-pill bg-danger bg-glow';
            $type = 'Corporate Resources';
        }
        return '<span class="' . $class . '">' . $type . '</span>';
    }

    public static function getApprove($approve_status,$id) {
        if (!empty($approve_status) && $approve_status == 1) {
            $class = 'badge badge-info';
            $style = 'font-size:12px';
            $approve_status = '<a href=""class="badge badge-info  text-white" id="'.$id.'" style="'.$style.'" name="'.$approve_status.'" onclick="ApprovedStatusChange(this.id,this.name,event)">
            Approve</a>';
                return '<span class="' . $class . '" style="font-size:12px; border-radius:4px; padding:2px">' . $approve_status . '</span>';

        } else {
            $class = 'badge badge-danger';
            $approve_status = '<a href=""class="text-white" id="'.$id.'" style="text-decoration: none;" name="'.$approve_status.'" onclick="ApprovedStatusChange(this.id,this.name,event)">
            Disapprove</a>';
        return '<span class="' . $class . '" style="font-size:12px; border-radius:4px; padding:4px">' . $approve_status . '</span>';
        }
    }

    public static function showErrorPublic($param, $msg = 'Sorry! Something went wrong! ')
    {
        $j = strpos($param, '(SQL:');
        if ($j > 15) {
            $param = substr($param, 8, $j - 9);
        }
        return $msg . $param;
    }

    public static function file_upload($request, $file_name, $upload_dir)
    {
        if ($request->hasFile($file_name)) {
            $file = $request->$file_name;
            $filename = time() . '_' . $file->getClientOriginalName();
            $up_path = "assets/uploads/".date('Y-m')."/$upload_dir/";
            $path = $file->move($up_path, $filename);
            if ($file->getError()) {
                $request->session()->flash('warning', $file->getErrorMessage());
                return false;
            }
            return $path;
        }
    }

}
