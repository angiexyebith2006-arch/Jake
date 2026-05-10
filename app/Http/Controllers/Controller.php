<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    
    protected function hasRole($roleName, $ministerioId = null)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

       
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole($roleName, $ministerioId);
    }

    
    protected function hasPermission($permissionName)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->canDo($permissionName);
    }

    
    protected function getUserMinisterios($roleName = null)
    {
        $user = auth()->user();
        
        if (!$user) {
            return collect();
        }

        if ($roleName) {
            return $user->ministeriosComo($roleName);
        }

        return $user->ministerios;
    }
}