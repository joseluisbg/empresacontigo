<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
       Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'ap_p' => ['string', 'max:255'],
            'ap_m' => ['string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'discapacidad' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:13', 'min:12'],
            'curp' => ['required', 'string', 'max:13', 'min:13', 'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'ap_p' => $input['ap_p'],
            'ap_m' => $input['ap_m'],
            'direccion' => $input['direccion'],
            'discapacidad' => $input['discapacidad'],
            'cedula' => $input['cedula'],
            'rfc' => $input['rfc'],
            'curp' => $input['curp'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return $user->assignRole('administrador');
    }
}
