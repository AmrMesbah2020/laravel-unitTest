@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Product NO. {{$product->id}}</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name: {{$product->name}}</label>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price: {{$product->price}} $</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="qty">Quantity: {{$product->qty}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
