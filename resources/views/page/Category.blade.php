@extends('layout.app')
@section('content')
    <div class="fashion_section">
        <div id="electronic_main_slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @forelse ($groupedProducts as $key=> $Products)
                    <div class="carousel-item @if ($key == 0) active @endif">
                        <div class="container">
                            <h1 class="fashion_taital">{{ $categoryName }}</h1>
                            <div class="fashion_section_2">
                                <div class="row">
                                    @forelse ($Products as $Product)
                                        <div class="col-lg-4 col-sm-4">
                                            <div class="box_main">
                                                <h4 class="shirt_text">{{ $Product['product_name'] }}</h4>
                                                <p class="price_text">
                                                    {{ number_format($Product->product_price - $Product->product_price * ($Product->discount_percentage / 100), 2) }}
                                                    <del style="color: #262626;">{{ $Product['product_price'] }}</del>
                                                </p>
                                                @php
                                                    $images = is_array($Product->image)
                                                        ? $Product->image
                                                        : [$Product->image];

                                                    $firstImage = str_starts_with($images[0], 'Product/')
                                                        ? url('storage', $images[0])
                                                        : $images[0];
                                                @endphp
                                                <div class="electronic_img"><img src="{{ $firstImage }}">
                                                </div>
                                                <div class="btn_main">
                                                    <div class="buy_bt"><a href="#">{{ __('page.Buy Now') }}</a></div>
                                                    <div class="seemore_bt"><a href="#">{{ __('page.See More') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <a class="carousel-control-prev" href="#electronic_main_slider" role="button" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="carousel-control-next" href="#electronic_main_slider" role="button" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
@endsection
