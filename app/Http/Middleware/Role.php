<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\UnauthorizedException;

class Role
{

    public function handle($request, Closure $next,$role,$role2=null,$role3=null)
    {
        if ($role2) {
            if ($role3){
                $role = array($role,$role2,$role3);
            }
            else {
                $role = array($role,$role2);
            }
        }
        if (!$this->UserisAllow($role)){
            throw new UnauthorizedException('Forbidden.');
        }
        return $next($request);
    }

    private function UserisAllow($role)
    {
        $usuario = AuthUser();
        if ($usuario == null) {
            return false;
        }
        if (is_array($role)) {
            foreach ($role as $key => $item) {
                $roleInteger = config('role.'.$item);
                if ($usuario->rol % $roleInteger == 0) {
                    return true;
                }
            }
        }
        else{
            $roleInteger = config('role.'.$role);
            if ($usuario->rol % $roleInteger == 0) {
                return true;
            }
        }
        return 0;
    }
}
