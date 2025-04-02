<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('registerAdmin');

        $this->middleware('auth')->only('registerAdmin');
    }

    public function showRegistrationForm()
    {
        abort(404);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'      => ['required', 'string', 'max:255'],
            'middle_name'     => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'office_division' => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin'        => ['required', 'integer'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        return redirect($this->redirectPath());
    }

    public function registerAdmin(Request $request)
    {
        $this->validator($request->all())->validate();

        $admin = Auth::user();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($admin);

        return redirect()->back()->with('success', 'User registered successfully.');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if (auth()->user() && auth()->user()->is_admin == 1) {
            return User::create([
                'first_name'      => $data['first_name'],
                'middle_name'     => $data['middle_name'],
                'last_name'       => $data['last_name'],
                'office_division' => $data['office_division'],
                'email'           => $data['email'],
                'password'        => Hash::make('12345678'),
                'is_admin'        => (int) $data['is_admin'],
            ]);
        }

        return User::create([
            'first_name'      => $data['first_name'],
            'middle_name'     => $data['middle_name'],
            'last_name'       => $data['last_name'],
            'office_division' => $data['office_division'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'is_admin'        => (int) $data['is_admin'],
        ]);
    }
}
