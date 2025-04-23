@extends('layouts.layout')

@section('title', 'Create Customer')

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
        Add Customer
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
        <form method="post" action="{{ route('customers.store') }}">
            @csrf
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" />
            </div>
            <div class="form-group">
                <label for="customer_email">Customer Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" />
            </div>
            <div class="form-group">
                <label for="customer_pwd">Password</label>
                <input type="password" class="form-control" id="customer_pwd" name="customer_pwd" />
            </div>
            <div class="form-group">
                <label for="customer_firstname">First Name</label>
                <input type="text" class="form-control" id="customer_firstname" name="customer_firstname" value="{{ old('customer_firstname') }}" />
            </div>
            <div class="form-group">
                <label for="customer_lastname">Last Name</label>
                <input type="text" class="form-control" id="customer_lastname" name="customer_lastname" value="{{ old('customer_lastname') }}" />
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" />
            </div>
            <div class="form-group">
                <label for="ban">Ban Status</label>
                <select class="form-control" id="ban" name="ban">
                    <option value="0" {{ old('ban') == '0' ? 'selected' : '' }}>Not Banned</option>
                    <option value="1" {{ old('ban') == '1' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
            <button type="submit" class="btn btn-block btn-danger">Create Customer</button>
        </form>
    </div>
</div>

@endsection