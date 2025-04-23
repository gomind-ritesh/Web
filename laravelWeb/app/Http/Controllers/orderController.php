<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Food;
use App\Models\BillFood;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(['customer', 'billFoods.food'])->paginate(5);
        return view('index_order', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $foods = Food::where('available', '1')->get();
        return view('create_order', compact('customers', 'foods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'bill_discount' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,cancel',
            'reviewed' => 'required|in:0,1',
            'food_items' => 'required|array',
            'food_items.*.food_id' => 'required|exists:food,food_id',
            'food_items.*.quantity' => 'required|integer|min:1',
        ]);

        // Create the order
        $order = Order::create([
            'customer_id' => $storeData['customer_id'],
            'bill_discount' => $storeData['bill_discount'],
            'status' => $storeData['status'],
            'reviewed' => $storeData['reviewed'],
        ]);

        // Add food items to the order
        foreach ($storeData['food_items'] as $item) {
            BillFood::create([
                'bill_id' => $order->bill_id,
                'food_id' => $item['food_id'],
                'item_qty' => $item['quantity'],
            ]);
        }

        return redirect('/orders')->with('success', 'Order has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'billFoods.food'])->findOrFail($id);
        return view('show_order', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with(['customer', 'billFoods.food'])->findOrFail($id);
        $customers = Customer::all();
        $foods = Food::where('available', '1')->get();
        return view('edit_order', compact('order', 'customers', 'foods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateData = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'bill_discount' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,cancel',
            'reviewed' => 'required|in:0,1',
            'food_items' => 'required|array',
            'food_items.*.food_id' => 'required|exists:food,food_id',
            'food_items.*.quantity' => 'required|integer|min:1',
        ]);

        // Update the order
        $order = Order::findOrFail($id);
        $order->update([
            'customer_id' => $updateData['customer_id'],
            'bill_discount' => $updateData['bill_discount'],
            'status' => $updateData['status'],
            'reviewed' => $updateData['reviewed'],
        ]);

        // Remove existing food items
        BillFood::where('bill_id', $id)->delete();

        // Add updated food items
        foreach ($updateData['food_items'] as $item) {
            BillFood::create([
                'bill_id' => $id,
                'food_id' => $item['food_id'],
                'item_qty' => $item['quantity'],
            ]);
        }

        return redirect('/orders')->with('success', 'Order has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // First delete related records in the pivot table
        BillFood::where('bill_id', $id)->delete();
        
        // Then delete the order
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect('/orders')->with('success', 'Order has been deleted');
    }
}