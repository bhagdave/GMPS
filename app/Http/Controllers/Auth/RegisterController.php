<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Matrix\Matrix;
use App\Matrix\UserData;
use App\Organisation;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $matrix;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->middleware('guest');
        $this->matrix = $matrix;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255', 'unique:organisations,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $matrixUser = $this->registerMatrixUser($data['name'], $data['password']);
        $organisation = Organisation::create(['name' => $data['company_name']]);
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'main' => 1,
            'organisation_id' => $organisation->id,
            'matrix_user_id' => $matrixUser['user_id'],
            'matrix_device_id' => $matrixUser['device_id']
        ]);
        session(['matrix_access_token' => $matrixUser['access_token'] ]);
        $user->save();
        return $user;
    }

    private function registerMatrixUser($name, $password){
        $userData = new UserData($this->matrix);
        $name = str_replace(' ', '', $name);
        $regData = $userData->register($name, $password);
        return $regData;
    }
}
