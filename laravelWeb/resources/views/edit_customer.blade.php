@extends('layouts.layout')

@section('title', 'Edit Customer')

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
        Edit & Update Customer
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
        <form method="post" action="{{ route('customers.update', $customer->customer_id) }}">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $customer->customer_name }}" />
            </div>

            <div class="form-group">
                <label for="customer_email">Customer Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ $customer->customer_email }}" />
            </div>

            <div class="form-group">
                <label for="customer_pwd">Password</label>
                <input type="password" class="form-control" id="customer_pwd" name="customer_pwd" placeholder="Enter new password or leave blank" />
            </div>

            <div class="form-group">
                <label for="customer_firstname">First Name</label>
                <input type="text" class="form-control" id="customer_firstname" name="customer_firstname" value="{{ $customer->customer_firstname }}" />
            </div>

            <div class="form-group">
                <label for="customer_lastname">Last Name</label>
                <input type="text" class="form-control" id="customer_lastname" name="customer_lastname" value="{{ $customer->customer_lastname }}" />
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" />
            </div>

            <div class="form-group">
                <label for="ban">Ban Status</label>
                <select class="form-control" id="ban" name="ban">
                    <option value="0" @if($customer->ban == 0) selected @endif>Not Banned</option>
                    <option value="1" @if($customer->ban == 1) selected @endif>Banned</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-block btn-danger">Update Customer</button>
        </form>
    </div>
</div>

@endsection