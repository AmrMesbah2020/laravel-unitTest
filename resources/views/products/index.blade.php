@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Products
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('products.create') }}" class="btn btn-primary float-right">Add Product</a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse ($products as $product)
                          <tr>
                            <th scope="row">{{$product->id}}</th>
                            <td>{{$product->name}}</td>
                            <td>{{$product->price}}</td>
                            <td>{{$product->qty}}</td>
                            <td>
                                @if (auth()->user()->is_admin)
                                    <a class="btn btn-success mx-1" href="{{route('products.edit', $product->id)}}">Edit</a>
                                    <form method="POST" action="{{route('products.destroy', $product->id)}}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger mx-1" onclick="confirm('are you sure')" type="submit">Delete</button>
                                    </form>
                                @endif
                            </td>
                          </tr>
                          @empty
                            <tr>
                                <td colspan="5" class="text-center">No products yet.</td>
                            </tr>
                          @endforelse
                        </tbody>
                      </table>
                      {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
