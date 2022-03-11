<?php

namespace App\Services;

use App\Models\Notification;
use Auth;
use DB;

class NotificationService{

    public function createNotification($notification, $user_id){
        $notifications = new Notification();
        $notifications->perform_by = Auth::user() ? Auth::user()->id : null;
        $notifications->to_user = $user_id;
        $notifications->notification = $notification;
        $notifications->is_read = 0;
        $notifications->save();
        return true;
    }

    public function createNotificationForAllAdmins($notification){
        $admins = DB::table('users')->where("role", '1')->where("status", 1)->get();
        foreach ($admins as $admin) {
            self::createNotification($notification, $admin->id);
        }
    }

    public function createNotificationForAllManagers($notification){
        $managers = DB::table('users')->where("role", '2')->where("status", 1)->get();
        foreach ($managers as $manager) {
            self::createNotification($notification, $manager->id);
        }
    }

    public function getAllAdminEmails(){
        $admins = DB::table('users')->where("role", '1')->where("status", 1)->get();
        $emails = array();
        foreach ($admins as $admin) {
            array_push($emails, $admin->email);
        }
        return $emails;
    }

    public function getAllManagerEmails(){
        $managers = DB::table('users')->where("role", '2')->where("status", 1)->get();
        $emails = array();
        foreach ($managers as $manager) {
            array_push($emails, $manager->email);
        }
        return $emails;
    }
}
