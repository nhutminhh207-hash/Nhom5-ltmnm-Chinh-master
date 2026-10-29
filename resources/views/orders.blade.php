@extends('layout')
@section('content')
@if($admin)
    <div class="row my-3">
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 rounded shadow-sm border-0">
                <div class="small">Tổng doanh thu thực tế</div>
                <div class="fs-2 fw-bold">{{ number_format($totalRevenue) }} đ</div>
                <div class="text-white-50 small">* Đã trừ các đơn bị hủy</div>
            </div>
        </div>
    </div>
@endif
<h2>{{ $admin?'Quản lý đơn hàng':'Đơn hàng của tôi' }}</h2>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Người nhận</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
                <th></th>
            </tr>
        </thead>
        <tbody>@forelse($orders as $o)<tr>
                <td>#{{ $o->id }}</td>
                <td>{{ $o->recipient_name }}</td>
                <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ __('shop.status.'.$o->status) }}</td>
                <td>{{ number_format($o->total_amount) }} đ</td>
                <td><a href="{{ url(($admin?'/admin':'').'/orders/'.$o->id) }}">Chi tiết</a></td>
            </tr>@empty<tr>
                <td colspan="6">Chưa có đơn hàng.</td>
            </tr>@endforelse</tbody>
    </table>
</div>
<h2>{{ $admin ? 'Quản lý đơn hàng' : 'Đơn hàng của tôi' }}</h2>

{{-- Thêm thẻ hiển thị tổng doanh thu dành riêng cho Admin --}}

{{ $orders->links() }}
@endsection