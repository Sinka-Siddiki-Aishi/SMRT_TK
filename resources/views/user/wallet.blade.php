@extends('layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Wallet</div>

                <div class="card-body">
                    <p>Current Balance: ${{ auth()->user()->wallet->balance }}</p>

                    <form action="{{ route('user.wallet.recharge') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Recharge</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection