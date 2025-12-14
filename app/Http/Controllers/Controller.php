<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Verificar si el usuario tiene un rol específico
     */
    protected function hasRole($roleName, $ministerioId = null)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Administrador total tiene acceso a todo
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole($roleName, $ministerioId);
    }

    /**
     * Verificar si el usuario tiene un permiso
     */
    protected function hasPermission($permissionName)
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return $user->canDo($permissionName);
    }

    /**
     * Obtener ministerios del usuario
     */
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