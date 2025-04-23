@extends('layouts.layout')

@section('title','List Orders')

@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
  .food-details {
    display: none;
    margin: 15px 0;
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
  }
  .expand-btn {
    cursor: pointer;
    color:rgb(0, 0, 0);
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
          <td>Bill ID</td>
          <td>Bill Date</td>
          <td>Customer Name</td>
          <td>Bill Discount</td>
          <td>Status</td>
          <td>Reviewed</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{$order->bill_id}}</td>
            <td>{{$order->bill_date}}</td>
            <td>{{$order->customer->customer_name}}</td>
            <td>Rs{{number_format($order->bill_discount, 2)}}</td>
            <td>
                <span class="badge {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'cancel' ? 'bg-danger' : 'bg-warning') }}">
                    {{ucfirst($order->status)}}
                </span>
            </td>
            <td>{{$order->reviewed == '1' ? 'Yes' : 'No'}}</td>
            <td class="text-center">
                <button class="btn btn-info btn-sm expand-btn" onclick="toggleFoodDetails({{ $order->bill_id }})">View Items</button>
                <a href="{{ route('orders.edit', $order->bill_id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('orders.destroy', $order->bill_id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <div id="food-details-{{ $order->bill_id }}" class="food-details">
                    <h5>Order Items</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Food Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($order->billFoods as $billFood)
                                @php 
                                    $discountedPrice = $billFood->food->food_price * (1 - $billFood->food->food_discount / 100);
                                    $itemTotal = $discountedPrice * $billFood->item_qty;
                                    $total += $itemTotal;
                                @endphp
                                <tr>
                                    <td>{{ $billFood->food->food_name }}</td>
                                    <td>Rs{{ number_format($billFood->food->food_price, 2) }}</td>
                                    <td>{{ $billFood->item_qty }}</td>
                                    <td>{{ number_format($billFood->food->food_discount, 2) }}%</td>
                                    <td>Rs{{ number_format($itemTotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Subtotal:</th>
                                <th>Rs{{ number_format($total, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Bill Discount:</th>
                                <th>Rs{{ number_format($order->bill_discount, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Total:</th>
                                <th>Rs{{ number_format($total - $order->bill_discount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  @if($orders->isEmpty())
    <p class="text-center">No orders found.</p>
  @endif
</div>

<script>
    function toggleFoodDetails(billId) {
        const detailsDiv = document.getElementById('food-details-' + billId);
        if (detailsDiv.style.display === 'block') {
            detailsDiv.style.display = 'none';
        } else {
            detailsDiv.style.display = 'block';
        }
    }
</script>

@endsection