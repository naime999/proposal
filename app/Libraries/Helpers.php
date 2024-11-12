<?php

use App\Models\ContactUsInformation;
use App\Models\PrivacyPolicy;
use App\Models\Term;
use App\Models\Setting;
use App\Models\Page;

if (!function_exists('contactUsInformation')) {
    function contactUsInformation()
    {
        $contactUsInformation = ContactUsInformation::where('status',1)->first();
        return $contactUsInformation;
    }
}

if (!function_exists('settings')) {
    function settings($settingName)
    {
        $settingsRow = Setting::where('setting_name', $settingName)->get();
        $settings = genarateSettingsData($settingsRow);
        return $settings;
    }

    function genarateSettingsData($datas)
    {
        if($datas->count() > 0){
            $newData = array();
            foreach($datas as $key => $data){
                $newData[$data->type] = $data->value;
            }
            return (object) $newData;
        }else{
            return null;
        }
    }
}

if (!function_exists('pages')) {
    function pages($pageName, $pageSection)
    {
        $pagesRow = Page::where('page_name', $pageName)->where('page_section', $pageSection)->get();
        $pages = genaratePageData($pagesRow);
        return $pages;
    }

    function genaratePageData($datas)
    {
        if($datas->count() > 0){
            $newData = array();
            foreach($datas as $key => $data){
                $newData[$data->type] = $data->value;
            }
            return (object) $newData;
        }else{
            return null;
        }
    }
}

if (!function_exists('moneyFormat')) {
    function moneyFormat($amount)
    {
        setlocale(LC_MONETARY, 'en_US');
        return number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('privacyPolicy')) {
    function privacyPolicy()
    {
        $privacyPolicy = PrivacyPolicy::where('status',1)->first();
        return $privacyPolicy;
    }
}

if (!function_exists('termsCondition')) {
    function termsCondition()
    {
        $termsCondition = Term::where('status',1)->first();
        return $termsCondition;
    }
}
