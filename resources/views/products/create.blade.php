@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Product</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="mb-3">
                          <label for="name" class="form-label">Name</label>
                          <input type="test" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                          <label for="price" class="form-label">Price</label>
                          <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="qty">Quantity</label>
                          <input type="number" class="form-control" id="qty" name="qty">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
