<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'role' => ['required', 'in:cliente,organizador'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        // Si el rol es cliente → se pide apellido
        if (isset($data['role']) && $data['role'] === 'cliente') {
            $rules['apellido'] = ['required', 'string', 'max:255'];
        }

        // Si el rol es organizador → se pide descripción y foto obligatoria
        if (isset($data['role']) && $data['role'] === 'organizador') {
            $rules['descripcion'] = ['required', 'string', 'max:1000'];
            $rules['foto_perfil'] = ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        // Subida de imagen (opcional para clientes)
        $fotoPath = null;

        if (isset($data['foto_perfil']) && $data['foto_perfil'] instanceof \Illuminate\Http\UploadedFile) {
            $fotoPath = $data['foto_perfil']->store('fotos_perfil', 'public');
        }

        $user = User::create([
            'role' => $data['role'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'apellido' => $data['role'] === 'cliente' ? $data['apellido'] ?? null : null,
            'descripcion' => $data['role'] === 'organizador' ? $data['descripcion'] ?? null : null,
            'foto_perfil' => $fotoPath,
        ]);

        return $user;
    }
}
