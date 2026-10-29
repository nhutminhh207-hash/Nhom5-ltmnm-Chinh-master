@extends('layout')
@section('content')

<!-- Leaflet CSS for interactive map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<p class="text-muted text-center pt-2 mb-0">Thông tin địa điểm & vị trí Showroom chính thức của Zara tại TP. Hồ Chí Minh.</p>

<div class="container-fluid bg-secondary mb-4">
  <div class="d-flex flex-column align-items-center justify-content-center" style="min-height:180px">
    <h1 class="font-weight-semi-bold text-uppercase mb-2">Hệ thống Showroom & Cửa hàng</h1>
    <div class="d-inline-flex">
      <a href="/" class="text-dark font-weight-medium">Trang chủ</a>
      <span class="px-2 text-dark">-</span>
      <span class="text-primary font-weight-bold">Bản đồ & Cửa hàng</span>
    </div>
  </div>
</div>

<div class="container-fluid pt-2 px-xl-5 pb-5">

  <div class="row mb-4">
    <!-- Weather Widget -->
    <div class="col-12">
      <div class="card shadow-sm border-0 bg-white mb-4">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-md-3 text-center border-right">
              <i class="fa fa-sun fa-4x text-warning mb-2"></i>
              <h3 class="font-weight-bold mb-0 text-dark">31°C</h3>
              <p class="text-muted mb-0">TP. Hồ Chí Minh — Nắng ấm</p>
            </div>
            <div class="col-md-9 pt-3 pt-md-0 pl-md-4">
              <h5 class="font-weight-bold text-primary mb-2">
                <i class="fa fa-tshirt mr-2"></i>Gợi ý trang phục thời trang phù hợp hôm nay
              </h5>
              <p class="mb-2 text-dark">
                Thời tiết nắng ráo với nhiệt độ lý tưởng cho các hoạt động ngoài trời. Zara gợi ý bạn chọn các mẫu <strong>Áo thun cotton thoáng khí</strong> hoặc <strong>Áo sơ mi ngắn tay</strong> kết hợp với <strong>Quần short/kaki mát mẻ</strong>.
              </p>
              <div>
                <a href="/shop?category_id=1" class="btn btn-sm btn-outline-primary mr-2 font-weight-bold">
                  Khám phá Áo thun mát mẻ &rarr;
                </a>
                <a href="/shop?category_id=2" class="btn btn-sm btn-outline-secondary font-weight-bold">
                  Xem Áo sơ mi &rarr;
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Map Container -->
    <div class="col-lg-8 mb-4">
      <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 font-weight-bold text-dark">
            <i class="fa fa-map-marked-alt text-primary mr-2"></i>Bản đồ vị trí Showroom Zara
          </h5>
          <span class="badge badge-pill badge-primary">1 Cơ sở hoạt động</span>
        </div>
        <div class="card-body p-0">
          <div id="showroomMap" style="height: 480px; width: 100%; border-radius: 0 0 6px 6px;"></div>
        </div>
      </div>
    </div>

    <!-- Store Info Card -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="mb-0 font-weight-bold text-dark">
            <i class="fa fa-store text-primary mr-2"></i>Thông tin cửa hàng
          </h5>
        </div>
        <div class="card-body p-3" style="max-height: 480px; overflow-y: auto;">
          @php
          $defaultShowroom = (object)[
          'name' => 'Zara Store',
          'address' => '140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP. Hồ Chí Minh',
          'phone' => '0123456789',
          'hours' => '08:00 - 22:00 (Tất cả các ngày)',
          'lat' => 10.8062,
          'lng' => 106.6286,
          'is_main' => true
          ];
          $items = [$defaultShowroom];
          @endphp

          @foreach($items as $s)
          <<div class="border border-dark rounded p-3 mb-3 showroom-card bg-white"
            style="cursor: pointer;"
            data-lat="{{ $s->lat }}"
            data-lng="{{ $s->lng }}"
            data-name="{{ $s->name }}"
            onclick="panToLocation(this.dataset.lat, this.dataset.lng, this.dataset.name)">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="font-weight-bold text-dark mb-0">{{ $s->name }}</h6>
              <span class="badge badge-primary">Trụ sở chính</span>
            </div>
            <p class="small text-muted mb-2"><i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $s->address }}</p>
            <div class="small mb-1">
              <i class="fa fa-phone-alt text-success mr-1"></i> <strong>Hotline:</strong> {{ $s->phone }}
            </div>
            <div class="small text-muted mb-2">
              <i class="fa fa-clock text-warning mr-1"></i> <strong>Mở cửa:</strong> {{ $s->hours }}
            </div>
            <a href="https://www.google.com/maps/search/?api=1&query=140+Lê+Trọng+Tấn,+Tây+Thạnh,+Tân+Phú,+TP.+Hồ+Chí+Minh" target="_blank" class="btn btn-sm btn-outline-danger btn-block mt-2 font-weight-bold">
              <i class="fa fa-map-marked-alt mr-1"></i> Mở trên ứng dụng Google Maps
            </a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
  let map;
  const defaultLocation = {
    name: 'Z Store',
    address: '140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP. Hồ Chí Minh',
    phone: '028 3816 1166',
    hours: '08:00 - 22:00 (Tất cả các ngày)',
    lat: 10.8062,
    lng: 106.6286,
    is_main: true
  };

  const showrooms = [defaultLocation];

  document.addEventListener('DOMContentLoaded', function() {
    const store = showrooms[0];

    // Khởi tạo bản đồ canh giữa tại 140 Lê Trọng Tấn
    map = L.map('showroomMap').setView([store.lat, store.lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors | Zara Store Locator'
    }).addTo(map);

    // Thêm ghim vị trí cho cơ sở 140 Lê Trọng Tấn
    const marker = L.marker([store.lat, store.lng]).addTo(map);
    marker.bindPopup(`
      <div style="min-width: 200px;">
        <strong style="color: #D19C97;">${store.name}</strong><br>
        <small>${store.address}</small><br>
        <small><strong>ĐT:</strong> ${store.phone}</small><br>
        <small><strong>Giờ mở cửa:</strong> ${store.hours}</small>
      </div>
    `).openPopup();
  });

  function panToLocation(lat, lng, name) {
    if (map) {
      map.flyTo([lat, lng], 16, {
        duration: 1.5
      });
    }
  }
</script>

@endsection