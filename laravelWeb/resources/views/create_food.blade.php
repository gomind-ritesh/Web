@extends('layouts.layout')

@section('title', 'Create Food')

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
    Add Food Item
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
      <form method="post" action="{{ route('foods.store') }}">
          <div class="form-group">
              @csrf
              <label for="food_name">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="food_name" value="{{ old('food_name') }}" maxlength="200" required/>
          </div>
          <div class="form-group">
              <label for="food_price">Price (Rs) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" class="form-control" name="food_price" value="{{ old('food_price') }}" required/>
          </div>
          <div class="form-group">
              <label for="food_discount">Discount (%) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" max="100" class="form-control" name="food_discount" value="{{ old('food_discount', 0) }}" required/>
          </div>
          <div class="form-group">
              <label for="food_category">Category <span class="text-danger">*</span></label>
              <select class="form-control" name="food_category" required>
                  <option value="">Select Category</option>
                  <option value="Appetizer" {{ old('food_category') == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
                  <option value="Main Course" {{ old('food_category') == 'Main Course' ? 'selected' : '' }}>Main Course</option>
                  <option value="Dessert" {{ old('food_category') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
              </select>
          </div>
          <div class="form-group">
              <label for="food_type">Type <span class="text-danger">*</span></label>
              <select class="form-control" name="food_type" required>
                  <option value="">Select Type</option>
                  <option value="Veg" {{ old('food_type') == 'Veg' ? 'selected' : '' }}>Veg</option>
                  <option value="Non-veg" {{ old('food_type') == 'Non-veg' ? 'selected' : '' }}>Non-veg</option>
              </select>
          </div>
          <div class="form-group">
              <label for="food_source">Source <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="food_source" value="{{ old('food_source') }}" required/>
          </div>
          <div class="form-group">
              <label for="available">Availability <span class="text-danger">*</span></label>
              <select class="form-control" name="available" required>
                  <option value="1" {{ old('available', '1') == '1' ? 'selected' : '' }}>Available</option>
                  <option value="0" {{ old('available') == '0' ? 'selected' : '' }}>Not Available</option>
              </select>
          </div>
          <button type="submit" class="btn btn-block btn-success">Create Food Item</button>
      </form>
  </div>
</div>
@endsection