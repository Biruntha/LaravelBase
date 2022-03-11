<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            // Roles
            // ['name' => "can-add-role",'display_name' => "Can add role",'type' => "Role"],
            // ['name' => "can-view-role",'display_name' => "Can view role",'type' => "Role"],
            // ['name' => "can-edit-role",'display_name' => "Can edit role",'type' => "Role"],
            // ['name' => "can-delete-role",'display_name' => "Can delete role",'type' => "Role"],

            // // Sites
            // ['name' => "can-add-site",'display_name' => "Can add site",'type' => "Site"],
            // ['name' => "can-view-site",'display_name' => "Can view site",'type' => "Site"],
            // ['name' => "can-edit-site",'display_name' => "Can edit site",'type' => "Site"],
            // ['name' => "can-delete-site",'display_name' => "Can delete site",'type' => "Site"],

            // // Fields
            // ['name' => "can-add-field",'display_name' => "Can add field",'type' => "Field"],
            // ['name' => "can-view-field",'display_name' => "Can view field",'type' => "Field"],
            // ['name' => "can-edit-field",'display_name' => "Can edit field",'type' => "Field"],
            // ['name' => "can-delete-field",'display_name' => "Can delete field",'type' => "Field"],

            // // Languages
            // ['name' => "can-add-language",'display_name' => "Can add language",'type' => "Language"],
            // ['name' => "can-view-language",'display_name' => "Can view language",'type' => "Language"],
            // ['name' => "can-edit-language",'display_name' => "Can edit language",'type' => "Language"],
            // ['name' => "can-delete-language",'display_name' => "Can delete language",'type' => "Language"],

        ]);

        /**
        DB::table('roles')->insert([
            'name' => "Admin"
        ]);
        DB::table('roles')->insert([
            'name' => "Manager"
        ]);**/


        //$admin = DB::table('roles')->where('name', 'Admin')->first()->id;

        //DB::table('role_user')->insert(['user_id'=>1 , 'role_id'=>$admin]);
        $permissions = Permission::all(); //

        foreach ($permissions as $key => $permission) {
           PermissionRole::updateOrCreate(['permission_id'=>$permission->id , 'role_id'=>1]);
           UserPermission::updateOrCreate(['permission_id'=>$permission->id , 'user_id'=>1]);
        }
    }
}
