@extends('layouts.layout')

@section('title', 'Edit Food')

@section('content')
<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
</style>

<div class="card push-top">
  <div class="card-header">
    Edit & Update Food Item
  </div>

  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('foods.update', $food->food_id) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
              <label for="food_name">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="food_name" value="{{ $food->food_name }}" maxlength="200" required/>
          </div>
          <div class="form-group">
              <label for="food_price">Price ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" class="form-control" name="food_price" value="{{ $food->food_price }}" required/>
          </div>
          <div class="form-group">
              <label for="food_discount">Discount (%) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" max="100" class="form-control" name="food_discount" value="{{ $food->food_discount }}" required/>
          </div>
          <div class="form-group">
              <label for="food_category">Category <span class="text-danger">*</span></label>
              <select class="form-control" name="food_category" required>
                  <option value="">Select Category</option>
                  <option value="Appetizer" {{ $food->food_category == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
                  <option value="Main Course" {{ $food->food_category == 'Main Course' ? 'selected' : '' }}>Main Course</option>
                  <option value="Dessert" {{ $food->food_category == 'Dessert' ? 'selected' : '' }}>Dessert</option>
              </select>
          </div>
          <div class="form-group">
              <label for="food_type">Type <span class="text-danger">*</span></label>
              <select class="form-control" name="food_type" required>
                  <option value="">Select Type</option>
                  <option value="Veg" {{ $food->food_type == 'Veg' ? 'selected' : '' }}>Veg</option>
                  <option value="Non-veg" {{ $food->food_type == 'Non-veg' ? 'selected' : '' }}>Non-veg</option>
              </select>
          </div>
          <div class="form-group">
              <label for="food_source">Source <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="food_source" value="{{ $food->food_source }}" required/>
          </div>
          <div class="form-group">
              <label for="available">Availability <span class="text-danger">*</span></label>
              <select class="form-control" name="available" required>
                  <option value="1" {{ $food->available == '1' ? 'selected' : '' }}>Available</option>
                  <option value="0" {{ $food->available == '0' ? 'selected' : '' }}>Not Available</option>
              </select>
          </div>
          <button type="submit" class="btn btn-block btn-success">Update Food Item</button>
      </form>
  </div>
</div>
@endsection