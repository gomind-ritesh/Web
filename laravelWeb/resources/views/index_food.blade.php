

@extends('layouts.layout')

@section('title', 'Food List')

@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
</style>

<div class="push-top">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
  <table class="table">
    <thead>
      <tr class="table-warning">
        <td>Food ID</td>
        <td>Name</td>
        <td>Price</td>
        <td>Discount</td>
        <td>Category</td>
        <td>Type</td>
        <td>Source</td>
        <td>Availability</td>
        <td class="text-center">Action</td>
      </tr>
    </thead>
    <tbody>
      @foreach($foods as $food)
      <tr>
        <td>{{ $food->food_id }}</td>
        <td>{{ $food->food_name }}</td>
        <td>${{ number_format($food->food_price, 2) }}</td>
        <td>{{ number_format($food->food_discount, 2) }}%</td>
        <td>{{ $food->food_category }}</td>
        <td>{{ $food->food_type }}</td>
        <td>{{ $food->food_source }}</td>
        <td>{{ $food->available == '1' ? 'Available' : 'Not Available' }}</td>
        <td class="text-center">
          <a href="{{ route('foods.edit', $food->food_id) }}" class="btn btn-primary btn-sm">Edit</a>
          <form action="{{ route('foods.destroy', $food->food_id) }}" method="post" style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @if($foods->isEmpty())
    <p class="text-center">No food items found.</p>
  @endif
  
  <div class="text-center mt-4">
    <a href="{{ route('foods.create') }}" class="btn btn-success">Add New Food Item</a>
  </div>
</div>
{{$foods->links()}}
@endsection