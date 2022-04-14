@php
use Illuminate\Support\Facades\Route;
@endphp
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">
                        @if (\Request::route()->getName() != 'home')
                            Trang chủ / {{ $breadcrumb }}
                        @endif

                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
