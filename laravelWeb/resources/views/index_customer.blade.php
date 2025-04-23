@extends('layouts.layout')

@section('title', 'Customer List')

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
      @foreach($customers as $customer)
      <tr>
        <td>{{ $customer->customer_id }}</td>
        <td>{{ $customer->customer_name }}</td>
        <td>{{ $customer->customer_email }}</td>
        <td>********</td> <!-- hidden password for security -->
        <td>{{ $customer->customer_firstname }}</td>
        <td>{{ $customer->customer_lastname }}</td>
        <td>{{ $customer->phone }}</td>
        <td>{{ $customer->ban == 1 ? 'Banned' : 'Not Banned' }}</td>
        <td class="text-center">
          <a href="{{ route('customers.show', $customer->customer_id) }}" class="btn btn-primary btn-sm">Show</a>
          <a href="{{ route('customers.edit', $customer->customer_id) }}" class="btn btn-primary btn-sm">Edit</a>
          <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="post" style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @if($customers->isEmpty())
    <p class="text-center">No customers found.</p>
  @endif
</div>
<!--pagination -->
{{$customers->links()}}

@endsection