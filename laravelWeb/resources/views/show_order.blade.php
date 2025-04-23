@extends('layouts.layout')

@section('title','View Order')

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
          <td>Order ID</td>
          <td>Bill Date</td>
          <td>Customer Name</td>
          <td>Bill Discount</td>
          <td>Status</td>
          <td>Reviewed</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$order->order_id}}</td>
            <td>{{$order->bill_date}}</td>
            <td>{{$order->customer->customer_name}}</td>
            <td>{{$order->bill_discount}}</td>
            <td>{{$order->status}}</td>
            <td>{{$order->reviewed ? 'Yes' : 'No'}}</td>
            <td class="text-center">
                <a href="{{ route('orders.edit', $order->order_id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('orders.destroy', $order->order_id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    </tbody>
  </table>
<div>
@endsection