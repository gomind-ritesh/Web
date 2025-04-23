<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::paginate(5);
        return view('index_customer' , ['customers' => $customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('create_customer');
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
            'customer_name' => 'required|max:200',
            'customer_email' => 'required|email|max:200',
            'customer_pwd' => 'required|max:200',
            'customer_firstname' => 'required|max:200',
            'customer_lastname' => 'required|max:2000',
            'phone' => 'required|regex:/^[0-9]{8}$/',
            'ban' => 'required|boolean',
        ]);
    
        // Hash the password before storing it
        $storeData['customer_pwd'] = Hash::make($storeData['customer_pwd']);
    
        $customer = Customer::create($storeData);
    
        return redirect('/customers')->with('success', 'Customer details have been saved!');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('show_customer', ['customer'=>$customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('edit_customer', compact('customer'));
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
            'customer_name' => 'required|max:200',
            'customer_email' => 'required|email|max:200',
            'customer_pwd' => 'max:200',
            'customer_firstname' => 'required|max:200',
            'customer_lastname' => 'required|max:2000',
            'phone' => 'required|regex:/^[0-9]{8}$/',
            'ban' => 'required|boolean',
        ]);
    
        // Hash the password before storing it
        $updateData['customer_pwd'] = Hash::make($updateData['customer_pwd']);
    
        Customer::where('customer_id', $id)->update($updateData);
        return redirect('/customers')->with('success', 'customer details have been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect('/customers')->with('success', 'customer has been deleted');
    }
}
