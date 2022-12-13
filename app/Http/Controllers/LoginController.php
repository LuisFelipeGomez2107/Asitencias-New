<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\users_has_status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class LoginController extends Controller
{
    use HasRoles;

    public function logout()
    {
        Auth::logout();
        return redirect('/');

    }
    public function index(Request $request){
        $user = User::where('email', $request->email)
        ->orWhere('phone', $request->email)
        ->first();
        $userHasStatus =users_has_status::where('user_id',$user->id)->where('status',1)->first();
        if ($user && Hash::check($request->password, $user->password) && $userHasStatus) {
            Auth::login($user);

        }

        if(Auth::check()) {
            $user =  Auth::user();

            $roleNames = $user->getRoleNames();
            // dd( $roleNames);
            if($roleNames[0] =='Admin'){
                return redirect()->route('app-user-list');


            }
            if($roleNames[0] =='Instalador' || $roleNames[0] =='Almacenista' || $roleNames[0] == 'Administrativo' || $roleNames[0]=='Mecanico'){
                return view('dashboard');

            }
            if($roleNames[0] =='Supervisor'){
                return redirect()->route('supervisor.index');
            }
            if($roleNames[0] == "SupervisorAsis"){
                return  view('redirect');
            }
        }else {
            return redirect('auth/login-basic');
        }
    }


    }








