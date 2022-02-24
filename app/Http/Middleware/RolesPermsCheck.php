<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesPermsCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        $role = Role::findByName($request->user()->role->first(),$guard);

        $route = $request->route();
        //$prefix = class_basename($route->getPrefix());

        //$controller = explode('@',class_basename($route->getActionName()));

        //$controllerName = strtolower($controller[0]);
        //$method = $route->getActionMethod();

        ///prefix_controllername_method
        $permissionName = $route->uri;


        if($role->checkPermissionTo($permissionName,$guard) == true){
            return $next($request);

        }

        abort(402, 'You don`t have permissions.');
    }
}
