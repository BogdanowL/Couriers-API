<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return User::with('orders')->latest()->paginate(6);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);
        $user = new User([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
        ]);
        $user->save();
        if ($request->has('order'))
        {
            $user->orders()->create([
            'user_id' => $user->id,
            'order' => $request->get('order')
            ]);
        }

    }

    public function show($id)
    {
        if ($id == null)
        {
            dd(123);
        }


        $query = User::with('orders')->where('id', $id)->orWhere('name', $id)->orWhere('surname', $id)
            ->orWhere('email', $id)->orWhere('phone', $id)->first();
        if (!$query)
        {
            return  response()->json(['error' => 'not in the database.'], 422);;
        }

        return $query;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);
        $user = User::find($id);
        $user->edit($request->all());
    }


    public function destroy($id)
    {
        User::find($id)->delete();
    }
}
