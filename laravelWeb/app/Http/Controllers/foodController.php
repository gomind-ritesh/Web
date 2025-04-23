<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::all();
        return view('index_food', ['foods' => $foods]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_food');
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
            'food_name' => 'required|max:200',
            'food_price' => 'required|numeric|min:0',
            'food_discount' => 'required|numeric|min:0|max:100',
            'food_category' => 'required|in:Appetizer,Main Course,Dessert',
            'food_type' => 'required|in:Veg,Non-veg',
            'food_source' => 'required',
            'available' => 'required|in:0,1',
        ]);
        
        $food = Food::create($storeData);
        
        return redirect('/foods')->with('success', 'Food item has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $food = Food::findOrFail($id);
        return view('show_food', ['food' => $food]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $food = Food::findOrFail($id);
        return view('edit_food', compact('food'));
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
            'food_name' => 'required|max:200',
            'food_price' => 'required|numeric|min:0',
            'food_discount' => 'required|numeric|min:0|max:100',
            'food_category' => 'required|in:Appetizer,Main Course,Dessert',
            'food_type' => 'required|in:Veg,Non-veg',
            'food_source' => 'required',
            'available' => 'required|in:0,1',
        ]);
        
        Food::where('food_id', $id)->update($updateData);
        return redirect('/foods')->with('success', 'Food item has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        $food->delete();
        
        return redirect('/foods')->with('success', 'Food item has been deleted');
    }
    
    /**
     * Get foods by category
     *
     * @param  string  $category
     * @return \Illuminate\Http\Response
     */
    public function getByCategory($category)
    {
        $foods = Food::where('food_category', $category)->get();
        return view('index_food', ['foods' => $foods, 'filter' => "Category: $category"]);
    }
    
    /**
     * Get foods by type
     *
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function getByType($type)
    {
        $foods = Food::where('food_type', $type)->get();
        return view('index_food', ['foods' => $foods, 'filter' => "Type: $type"]);
    }
}