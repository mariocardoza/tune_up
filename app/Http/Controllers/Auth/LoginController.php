<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function showLoginForm()
    {
        return view('auth.login');
    }
    public function username()
    {
        return 'username';
    }
     public function authenticate(Request $request)
    {
       // dd($request->all());
        $this->validar($request->all())->validate();
        if (Auth::attempt(['username' => $request->username, 
            'password' => $request->password])
            ) {
            //dd('hola');
            return redirect()->intended('home');
        }else{
            return redirect('/')->with('error','El nombre de usuario o contraseña no existe en nuestros registros');
        }
    }
    public function logout()
    {
        //Bitacora::bitacora('Cerró Sesión del sistema');
        session()->flush();
        Auth::logout();
        return redirect('/')->with('mensaje','Cerró Sesión del sistema exitósamente');
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validar(array $data)
    {
        $mensajes=array(
          'username.required'=>'El nombre de usuario es obligatorio',
          'password.required'=>'La contraseña es obligatoria',
      );
      return Validator::make($data, [
          'password'=>'required',
          'username'=>'required',
      ],$mensajes);
    }
}
