@extends('layouts.layout')

@section('title','Create Order')

@section('content')
<style>
    .push-top {
        margin-top: 50px;
    }
    .food-item {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        position: relative;
    }
    .remove-food {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: red;
    }
    .add-food-btn {
        margin-bottom: 20px;
    }
    .subtotal {
        font-weight: bold;
        margin-top: 10px;
        font-size: 16px;
    }
</style>

<div class="card push-top">
  <div class="card-header">
    Add Order
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

    <form method="post" action="{{ route('orders.store') }}" id="orderForm">
      @csrf
      <div class="form-group">
        <label for="bill_date">Bill Date</label>
        <input type="date" class="form-control" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}"/>
      </div>
      
      <div class="form-group">
        <label for="customer_id">Customer</label>
        <select name="customer_id" class="form-control" >
            @foreach($customers as $customer)
              <option value="{{$customer->customer_id}}" @selected(old('customer_id') == $customer->customer_id)>
                {{$customer->customer_name}}
              </option>
            @endforeach
        </select>
      </div>
      
      <!-- select food items -->
      <div class="form-group">
        <label>Food Items</label>
        <div id="food-items-container">
          <!-- add food items -->
          @if(old('food_items'))
            @foreach(old('food_items') as $index => $item)
              <div class="food-item">
                <span class="remove-food" onclick="removeFood(this)">&times;</span>
                <div class="row">
                  <div class="col-md-6">
                    <label>Food</label>
                    <select name="food_items[{{$index}}][food_id]" class="form-control food-select" onchange="updatePrice(this)">
                      @foreach($foods as $food)
                        <option value="{{$food->food_id}}" 
                          data-price="{{$food->food_price}}" 
                          data-discount="{{$food->food_discount}}"
                          @selected(old('food_items')[$index]['food_id'] == $food->food_id)>
                          {{$food->food_name}} - Rs{{$food->food_price}}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Quantity</label>
                    <input type="number" min="1" class="form-control quantity-input" 
                      name="food_items[{{$index}}][quantity]" 
                      value="{{ old('food_items')[$index]['quantity'] ?? 1 }}" 
                      onchange="updateSubtotal(this)">
                  </div>
                </div>
                <div class="subtotal">Subtotal: Rs<span class="item-subtotal">0.00</span></div>
              </div>
            @endforeach
          @endif
        </div>

        <button type="button" class="btn btn-success add-food-btn" onclick="addFoodItem()">
          <i class="fa fa-plus"></i> Add Food Item
        </button>
      </div>

      <div class="form-group">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h5>Order Total:</h5>
              </div>
              <div class="col-md-4 text-right">
                <h5>Rs<span id="order-total">0.00</span></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="bill_discount">Bill Discount (Rs)</label>
        <input type="number" step="0.01" min="0" class="form-control" name="bill_discount" 
          value="{{ old('bill_discount', 0) }}" id="bill-discount" onchange="updateFinalTotal()"/>
      </div>

      <div class="form-group">
        <div class="card bg-light">
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <h5>Final Amount:</h5>
              </div>
              <div class="col-md-4 text-right">
                <h5>Rs<span id="final-total">0.00</span></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" class="form-control">
            <option value="active" @selected(old('status', 'active') == 'active')>Active</option>
            <option value="completed" @selected(old('status') == 'completed')>Completed</option>
            <option value="cancel" @selected(old('status') == 'cancel')>Cancel</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="reviewed">Reviewed</label>
        <div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reviewed" id="reviewed-yes" value="1"
              @checked(old('reviewed') == '1')>
            <label class="form-check-label" for="reviewed-yes">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reviewed" id="reviewed-no" value="0"
              @checked(old('reviewed', '0') == '0')>
            <label class="form-check-label" for="reviewed-no">No</label>
          </div>
        </div>
      </div>
      
      <button type="submit" class="btn btn-block btn-danger">Add Order</button>
    </form>
  </div>
</div>

<script>
  // Template for new food item
  const foodItemTemplate = `
    <div class="food-item">
      <span class="remove-food" onclick="removeFood(this)">&times;</span>
      <div class="row">
        <div class="col-md-6">
          <label>Food</label>
          <select name="food_items[__INDEX__][food_id]" class="form-control food-select" onchange="updatePrice(this)">
            @foreach($foods as $food)
              <option value="{{$food->food_id}}" 
                data-price="{{$food->food_price}}" 
                data-discount="{{$food->food_discount}}">
                {{$food->food_name}} - Rs{{$food->food_price}}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label>Quantity</label>
          <input type="number" min="1" class="form-control quantity-input" 
            name="food_items[__INDEX__][quantity]" 
            value="1" 
            onchange="updateSubtotal(this)">
        </div>
      </div>
      <div class="subtotal">Subtotal: Rs<span class="item-subtotal">0.00</span></div>
    </div>
  `;

  let foodItemIndex = {{ old('food_items') ? count(old('food_items')) : 0 }};

  // Add a new food item
  function addFoodItem() {
    const container = document.getElementById('food-items-container');
    const newItem = foodItemTemplate.replace(/__INDEX__/g, foodItemIndex++);
    container.insertAdjacentHTML('beforeend', newItem);
    
    // Update the subtotal for the new item
    const newSelect = container.lastElementChild.querySelector('.food-select');
    updatePrice(newSelect);
    updateOrderTotal();
  }

  // Remove a food item
  function removeFood(element) {
    element.closest('.food-item').remove();
    updateOrderTotal();
  }

  // Update price based on selected food
  function updatePrice(selectElement) {
    const foodItem = selectElement.closest('.food-item');
    const quantityInput = foodItem.querySelector('.quantity-input');
    updateSubtotal(quantityInput);
  }

  // Update subtotal for a food item
  function updateSubtotal(element) {
    const foodItem = element.closest('.food-item');
    const select = foodItem.querySelector('.food-select');
    const quantity = foodItem.querySelector('.quantity-input').value;
    const option = select.options[select.selectedIndex];
    
    const price = parseFloat(option.dataset.price);
    const discount = parseFloat(option.dataset.discount);
    const discountedPrice = price * (1 - discount / 100);
    const subtotal = discountedPrice * quantity;
    
    foodItem.querySelector('.item-subtotal').textContent = subtotal.toFixed(2);
    updateOrderTotal();
  }

  // Update the order total
  function updateOrderTotal() {
    const subtotals = document.querySelectorAll('.item-subtotal');
    let total = 0;
    
    subtotals.forEach(subtotal => {
      total += parseFloat(subtotal.textContent);
    });
    
    document.getElementById('order-total').textContent = total.toFixed(2);
    updateFinalTotal();
  }

  // Update the final total after discount
  function updateFinalTotal() {
    const orderTotal = parseFloat(document.getElementById('order-total').textContent);
    const discount = parseFloat(document.getElementById('bill-discount').value) || 0;
    const finalTotal = Math.max(0, orderTotal - discount);
    
    document.getElementById('final-total').textContent = finalTotal.toFixed(2);
  }

  // Initialize the form on page load
  document.addEventListener('DOMContentLoaded', function() {
    // Add at least one food item if none exist
    if (document.querySelectorAll('.food-item').length === 0) {
      addFoodItem();
    } else {
      // Update all existing food items
      document.querySelectorAll('.food-select').forEach(select => {
        updatePrice(select);
      });
    }
  });

  // Form validation before submit
  document.getElementById('orderForm').addEventListener('submit', function(event) {
    const foodItems = document.querySelectorAll('.food-item');
    if (foodItems.length === 0) {
      event.preventDefault();
      alert('Please add at least one food item to the order.');
    }
  });
</script>
@endsection