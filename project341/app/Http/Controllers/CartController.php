<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $userId = auth()->user()->id;
        \Cart::session($userId)->add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => Product::find($request->id)->price,
            'quantity' => 1,
            'associatedModel' => Product::find($request->id),

        ]);
        $cartItems = \Cart::session($userId)->getContent();
        return view('cart', compact('cartItems', 'userId'));
    }
    public function cartList()
    {
        $userId = auth()->user()->id;
        $cartItems = \Cart::session($userId)->getContent();
        // dd($cartItems);

        return view('cart', compact('cartItems', 'userId'));
    }

    public function removeCart(Request $request)
    {
        $userId = auth()->user()->id;
        \Cart::session($userId)->remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');

        return redirect()->route('cart.list');
    }


    public function updateCart(Request $request)
    {
        \Cart::session(auth()->user()->id)->update($request->id, array(
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ],
        ));


        session()->flash('success', 'Item Cart is Updated Successfully !');

        return redirect()->route('cart.list');
    }
}
