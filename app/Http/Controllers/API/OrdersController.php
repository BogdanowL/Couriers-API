<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Orders;
use App\User;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function index()
    {
       return Orders::with('user')->latest()->paginate(6);
    }


    public function store(Request $request)
    {
        $request->validate([
            'addOrder' => 'required|max:255'
        ]);
        $user = User::find($request->get('id'));
        $user->orders()->create([
           'order' => $request->get('addOrder')
        ]);

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit' => 'required|max:255'
        ]);
        $order = Orders::find($id);
        $order->order = $request->get('edit');
        $order->save();
    }

    public function destroy($id)
    {
        Orders::find($id)->delete();
    }
}
