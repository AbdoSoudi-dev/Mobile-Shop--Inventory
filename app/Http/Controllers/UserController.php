<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function index()
    {
        return view("users.users")->withUsers(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ]);

        User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => Hash::make($validated['password']),
            "role_type" => "user",
        ]);

        return redirect("/users")->with([ "message" => "تم إضافة". $validated['name'] . " بنجاح" ]);
    }

    public function update_status(Request $request, $id)
    {
        $user = User::find($id);
        $user->removed = $user->removed == 0 ? 1 : 0;
        $user->save();
        return redirect("/users")->with([ "message" => "تم التعديل بنجاح" ]);
    }
}
