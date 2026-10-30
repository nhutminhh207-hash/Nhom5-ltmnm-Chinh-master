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
              <i id="weather-icon" class="fa fa-sun fa-4x text-warning mb-2"></i>
              <h3 id="weather-temp" class="font-weight-bold mb-0 text-dark">--°C</h3>
              <p class="text-muted mb-0">TP. Hồ Chí Minh — <span id="weather-desc">Đang tải...</span></p>
            </div>
            <div class="col-md-9 pt-3 pt-md-0 pl-md-4">
              <h5 class="font-weight-bold text-primary mb-2">
                <i class="fa fa-tshirt mr-2"></i>Gợi ý trang phục thời trang phù hợp hôm nay
              </h5>
              <p id="weather-suggestion" class="mb-2 text-dark">
                Đang cập nhật gợi ý thời tiết...
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
    name: 'Zara Store',
    address: '140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP. Hồ Chí Minh',
    phone: '028 3816 1166',
    hours: '08:00 - 22:00 (Tất cả các ngày)',
    lat: 10.8062,
    lng: 106.6286,
    is_main: true
  };

  const showrooms = [defaultLocation];

  document.addEventListener('DOMContentLoaded', function() {
    // 1. KHỞI TẠO BẢN ĐỒ LEAFLET
    const store = showrooms[0];
    map = L.map('showroomMap').setView([store.lat, store.lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors | Zara Store Locator'
    }).addTo(map);

    const marker = L.marker([store.lat, store.lng]).addTo(map);
    marker.bindPopup(`
      <div style="min-width: 200px;">
        <strong style="color: #D19C97;">${store.name}</strong><br>
        <small>${store.address}</small><br>
        <small><strong>ĐT:</strong> ${store.phone}</small><br>
        <small><strong>Giờ mở cửa:</strong> ${store.hours}</small>
      </div>
    `).openPopup();

    // 2. GỌI API THỜI TIẾT KHỞI TẠO VÀ XỬ LÝ GỢI Ý TRANG PHỤC
    const apiKey = '52a56f853a1363ee01f988a7ef28a989';
    const lat = 10.8062;
    const lon = 106.6286;

    fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=vi&appid=${apiKey}`)
      .then(response => response.json())
      .then(data => {
        if (data && data.cod === 200) {
          const temp = Math.round(data.main.temp);
          const desc = data.weather[0].description; // ví dụ: "mây đen u ám", "mưa nhẹ", "nắng ráo"...
          const weatherId = data.weather[0].id; // Mã thời tiết OpenWeatherMap
          const mainState = data.weather[0].main; // Rain, Clouds, Clear, Thunderstorm...

          // Cập nhật nhiệt độ & mô tả
          const tempElem = document.getElementById('weather-temp');
          const descElem = document.getElementById('weather-desc');
          if (tempElem) tempElem.innerText = `${temp}°C`;
          if (descElem) descElem.innerText = desc.charAt(0).toUpperCase() + desc.slice(1);

          // Xử lý Logic Gợi ý Trang Phục phù hợp với từng trạng thái API
          let iconClass = 'fa-sun text-warning';
          let suggestion = '';

          // TH 1: Mưa hoặc dông bão (Mã 2xx, 3xx, 5xx)
          if (weatherId >= 200 && weatherId < 600) {
            iconClass = 'fa-cloud-showers-heavy text-info';
            suggestion = 'Trời đang có mưa. Hãy ưu tiên các mẫu áo thun gọn nhẹ, chất liệu cotton mau khô và nhớ mang theo áo khoác chống nước nhẹ khi ra ngoài.';
          }
          // TH 2: Mây đen u ám / Âm u (Mã 804 hoặc mô tả chứa từ "u ám", "âm u")
          else if (weatherId === 804 || desc.includes('u ám') || desc.includes('âm u')) {
            iconClass = 'fa-cloud text-secondary';
            suggestion = 'Trời nhiều mây đen u ám và dễ có mưa bất chợt. Bạn nên diện outfit gọn nhẹ như áo thun, áo sơ mi khoác ngoài kết hợp quần kaki/jean năng động.';
          }
          // TH 3: Râm mát / Mây rải rác (Mã 801, 802, 803)
          else if (mainState === 'Clouds') {
            iconClass = 'fa-cloud-sun text-primary';
            suggestion = 'Thời tiết râm mát, dễ chịu. Rất thích hợp để phối outfit layer thời trang như áo sơ mi khoác ngoài áo thun, hoặc áo thun dài tay thanh lịch.';
          }
          // TH 4: Nắng nóng (Nhiệt độ >= 32°C hoặc Trời quang nắng gắt)
          else if (temp >= 32) {
            iconClass = 'fa-sun text-danger';
            suggestion = 'Trời khá oi bức và nắng nóng. Gợi ý bạn chọn các mẫu áo thun cotton thoáng khí, áo sơ mi ngắn tay chất liệu đũi/linen mát mẻ.';
          }
          // TH 5: Se lạnh (Nhiệt độ < 24°C)
          else if (temp < 24) {
            iconClass = 'fa-snowflake text-primary';
            suggestion = 'Thời tiết se lạnh dễ chịu. Phù hợp để diện áo cardigan, áo sweater mỏng hoặc áo khoác mỏng phối cùng quần dài.';
          }
          // TH 6: Mặc định (Nắng nhẹ / Đẹp trời)
          else {
            iconClass = 'fa-sun text-warning';
            suggestion = 'Thời tiết ráo đẹp với nhiệt độ lý tưởng cho các hoạt động ngoài trời. Bạn có thể thoải mái chọn áo thun cotton hoặc áo sơ mi ngắn tay mát mẻ.';
          }

          // Cập nhật DOM
          const iconElem = document.getElementById('weather-icon');
          const suggestionElem = document.getElementById('weather-suggestion');
          if (iconElem) iconElem.className = `fa ${iconClass} fa-4x mb-2`;
          if (suggestionElem) suggestionElem.innerText = suggestion;
        }
      })
      .catch(err => {
        console.error("Lỗi khi kết nối API thời tiết:", err);
      });
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