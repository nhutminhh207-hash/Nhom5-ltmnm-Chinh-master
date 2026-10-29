@extends('layout')
@section('content')
<h2>Đơn hàng #{{ $order->id }}</h2>
<p>{{ $order->recipient_name }} · {{ $order->phone }}</p>
<p>{{ $order->address }}</p>

{{-- Tự động hiển thị phương thức thanh toán theo dữ liệu trong database --}}
<p>
    Thanh toán: 
    <strong>
        @if($order->payment_method === 'bank_transfer')
            Chuyển khoản Ngân hàng (Quét mã QR)
        @elseif($order->payment_method === 'vnpay')
            Thanh toán qua VNPAY
        @else
            Thanh toán khi nhận hàng (COD)
        @endif
    </strong> 
    · Trạng thái: <strong>{{ __('shop.status.'.$order->status) }}</strong>
</p>

{{-- Thông báo xanh khi thanh toán thành công --}}
@if($order->payment_status === 'paid')
    <div class="alert alert-success my-3">
        ✓ Đơn hàng đã được thanh toán thành công!
    </div>
@endif

{{-- Hiển thị mã QR nếu chọn Chuyển khoản và CHƯA thanh toán --}}
@if(!$admin && $order->payment_method === 'bank_transfer' && $order->payment_status !== 'paid')
    <div class="my-4 p-3 border rounded text-center bg-light">
        <h5 class="text-primary fw-bold">Quét mã QR để thanh toán</h5>
        <p class="mb-2 text-muted">Mở App Ngân hàng bất kỳ để quét mã bên dưới:</p>
        
        <img src="https://img.vietqr.io/image/MB-0123456789-compact2.png?amount={{ $order->total_amount }}&addInfo=Thanh toan don hang {{ $order->id }}" 
             alt="Mã QR VietQR" 
             class="img-fluid border p-2 bg-white rounded shadow-sm mb-3" 
             style="max-width: 250px;">

        <div class="text-start d-inline-block bg-white p-3 rounded border w-100" style="max-width: 400px;">
            <p class="mb-1"><strong>Ngân hàng:</strong> MBBank</p>
            <p class="mb-1"><strong>Số tài khoản:</strong> <code>0123456789</code></p>
            <p class="mb-1"><strong>Số tiền:</strong> <strong class="text-danger">{{ number_format($order->total_amount) }} đ</strong></p>
            <p class="mb-0"><strong>Nội dung CK:</strong> <span class="badge bg-warning text-dark fs-6">Thanh toan don hang {{ $order->id }}</span></p>
        </div>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>SKU</th>
                <th>Size / màu</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i)
            <tr>
                <td>{{ $i->product_name }}</td>
                <td>{{ $i->sku }}</td>
                <td>{{ $i->size }} / {{ $i->color }}</td>
                <td>{{ number_format($i->unit_price) }} đ</td>
                <td>{{ $i->quantity }}</td>
                <td>{{ number_format($i->line_total) }} đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h4>Tổng: {{ number_format($order->total_amount) }} đ</h4>

@if($admin)
    <form method="post" action="{{ url('/admin/orders/'.$order->id.'/status') }}">
        @csrf 
        @method('PATCH')
        <select name="status" class="form-control mb-3">
            @foreach(\App\Services\OrderService::TRANSITIONS[$order->status] as $status)
                <option value="{{ $status }}">{{ __('shop.status.'.$status) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" @disabled(empty(\App\Services\OrderService::TRANSITIONS[$order->status]))>Cập nhật trạng thái</button>
    </form>
@elseif($order->status === 'pending')
    <form method="post" action="{{ url('/orders/'.$order->id.'/cancel') }}">
        @csrf 
        @method('PATCH')
        <button class="btn btn-danger">Hủy đơn hàng</button>
    </form>
@endif
@endsection