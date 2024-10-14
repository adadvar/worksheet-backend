<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

if (!function_exists('to_valid_mobile_number')) {
    function to_valid_mobile_number(string $mobile){
        return '+98' . substr($mobile, -10, 10);
    }
}

if (!function_exists('random_verification_code')) {
    function random_verification_code () {
        if(config('app.env') === 'local')
            return '111111';
        return random_int(100000, 999999);
    }
}

if(!function_exists('clear_storage')){
    function clear_storage(string $storageName){ 
        try{
            Storage::disk($storageName)->delete(Storage::disk($storageName)->allFiles());
            foreach(Storage::disk($storageName)->allDirectories() as $dir){
                Storage::disk($storageName)->deleteDirectory($dir);
            }
            return true;
        }catch(Exception $e){
            Log::error($e);
            return false;
        }
    }
}
if(!function_exists('client_ip')){
    function client_ip($withDate = false){
        $ip = $_SERVER['REMOTE_ADDR'] . '-' . md5($_SERVER['HTTP_USER_AGENT']);
        
        if($withDate){
            $ip .= '-' . now()->toDateString();
        }
        return $ip;
    }
}

if(!function_exists('sort_comments')){
    function sort_comments($comments, $parentId = null){
         $result = [];
         foreach($comments as $comment){
            if ($comment->parent_id === $parentId){
                $data = $comment->toArray();
                $data['children'] = sort_comments($comments, $comment->id);
                $result[] = $data;
            }
         }

         return $result;
    }
}

if(!function_exists('functionName')){
    function functionName(){
        
    }
}