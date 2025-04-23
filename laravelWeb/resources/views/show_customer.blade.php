@extends('layouts.layout')

@section('title', 'List Customers')

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
        <td>Customer ID</td>
        <td>Name</td>
        <td>Email</td>
        <td>Password</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Phone</td>
        <td>Ban Status</td>
        <td class="text-center">Action</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $customer->customer_id }}</td>
        <td>{{ $customer->customer_name }}</td>
        <td>{{ $customer->customer_email }}</td>
        <td>********</td> <!-- Masked password for security -->
        <td>{{ $customer->customer_firstname }}</td>
        <td>{{ $customer->customer_lastname }}</td>
        <td>{{ $customer->phone }}</td>
        <td>{{ $customer->ban == 1 ? 'Banned' : 'Not Banned' }}</td>
        <td class="text-center">
          <a href="{{ route('customers.edit', $customer->customer_id) }}" class="btn btn-primary btn-sm">Edit</a>
          <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="post" style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    </tbody>
  </table>

  Orders
  <table class="table">
    <thead>
      <tr class="table-warning">
        <td>Bill ID</td>
        <td>Bill Date</td>
        <td>Discount</td>
        <td>Status</td>
        <td>Reviewed</td>
        <td>Customer ID</td>
        <td class="text-center">Action</td>
      </tr>
    </thead>
    <tbody>
      @foreach($customer->orders as $order)
      <tr>
        <td>{{ $order->bill_id }}</td>
        <td>{{ $order->bill_date }}</td>
        <td>{{ $order->bill_discount }}</td>
        <td>{{ $order->status }}</td>
        <td>{{ $order->reviewed == '1' ? 'Yes' : 'No' }}</td>
        <td>{{ $order->customer_id }}</td>
        <td class="text-center">
          <a href="{{ route('orders.edit', $order->bill_id) }}" class="btn btn-primary btn-sm">Edit</a>
          <form action="{{ route('orders.destroy', $order->bill_id) }}" method="post" style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @if($customer->orders->isEmpty())
    <p class="text-center">No orders found for this customer.</p>
  @endif
</div>

@endsection