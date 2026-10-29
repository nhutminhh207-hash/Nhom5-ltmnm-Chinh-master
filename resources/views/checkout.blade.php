@extends('layout')
@section('content')
<h2>Xác nhận đặt hàng</h2>
<div class="row">
    <div class="col-md-7">
        <form method="post" action="{{ url('/checkout') }}">
            @csrf
            <input type="hidden" name="checkout_token" value="{{ $token }}">

            <label class="form-label">Người nhận</label>
            <input name="recipient_name" required class="form-control mb-3" value="{{ old('recipient_name', auth()->user()->name) }}">

            <label class="form-label">Số điện thoại</label>
            <input name="phone" required class="form-control mb-3" value="{{ old('phone') }}">

            <label class="form-label">Địa chỉ giao hàng</label>
            <textarea name="address" required class="form-control mb-3">{{ old('address') }}</textarea>

            <div class="mb-4">
                <label class="form-label fw-bold">Phương thức thanh toán:</label>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                    <label class="form-check-label" for="payment_cod">
                        Thanh toán khi nhận hàng (COD)
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer">
                    <label class="form-check-label" for="payment_bank">
                        Chuyển khoản Ngân hàng (Quét mã QR)
                    </label>
                </div>
            </div>

            <button class="btn btn-primary" @disabled($items->isEmpty())>Xác nhận đặt hàng</button>
        </form>
    </div>

    {{-- Cột hiển thị tóm tắt đơn hàng --}}
    <div class="col-md-5">
        <div class="p-4 bg-light rounded border">
            <h4>Đơn hàng</h4>
            <hr>
            @foreach($items as $i)
            <p>{{ $i->variant->product->name }} · {{ $i->variant->size }}/{{ $i->variant->color }} × {{ $i->quantity }}: <strong>{{ number_format($i->variant->price * $i->quantity) }} đ</strong></p>
            @endforeach
            <hr>
            <strong class="fs-5">Tổng: {{ number_format($items->sum(fn($i) => $i->variant->price * $i->quantity)) }} đ</strong>
        </div>
    </div>
</div>
@endsection