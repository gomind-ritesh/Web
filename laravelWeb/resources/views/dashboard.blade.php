<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="p-4 text-blue-900">Page Options</h3>
                <div class="p-6 text-gray-900 space-x-12" style="border-style: solid;">
                    <ul>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('customers.index') }}">View Customers</a>
                        </li>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('customers.create') }}">Create Customer</a>
                        </li>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('orders.index') }}">View Orders</a>
                        </li>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('orders.create') }}">Create Order</a>
                        </li>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('foods.index') }}">View Foods</a>
                        </li>
                        <li class="p-3 mb-2 border border-gray-300 bg-blue-100 rounded">
                            <a href="{{ route('foods.create') }}">Create Food</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>