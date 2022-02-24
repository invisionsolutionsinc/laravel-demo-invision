<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $value) {

            $prefix = class_basename($value->getPrefix());

            //$controller = explode('@',class_basename($value->getActionName()));

            //$controllerName = strtolower($controller[0]);
            //$method = $value->getActionMethod();

            ///prefix_controllername_method
            //$permissionName = $prefix.'_'.$controllerName.'_'.$method;


            if($prefix == 'iadus' && in_array('role_perms:clients',$value->gatherMiddleware())){


                $permission = Permission::updateOrcreate(['name' => $value->uri]);

                $role = Role::findById('1');
                if(!$role->hasPermissionTo($permission->name)){
                    $role->givePermissionTo($permission->name);
                }

            }
        }
    }
}
