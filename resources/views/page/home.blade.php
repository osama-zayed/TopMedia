@extends('layout.app')
@section('content')
    @forelse (\App\Models\Category::take(10)->get()   as $Category)
        @php
            $products = \App\Models\Product::select(
                'id',
                'product_name',
                'product_price',
                'image',
                'discount_percentage',
                'category_id',
            )
                ->where('product_status', 1)
                ->where('category_id', $Category->id)
                ->orderByDesc('id')
                ->get();

            $groupedProducts = [];
            $currentGroup = [];

            foreach ($products as $product) {
                $currentGroup[] = $product;

                if (count($currentGroup) === 3) {
                    $groupedProducts[] = $currentGroup;
                    $currentGroup = [];
                }
            }

            if (!empty($currentGroup)) {
                $groupedProducts[] = $currentGroup;
            }

        @endphp
        <div class="fashion_section">
            <div id='{{ $Category->id }}' class="carousel product_main_slider slide" data-ride="carousel">
                <div class="carousel-inner">
                    @forelse ($groupedProducts as $key=> $Products)
                        <div class="carousel-item @if ($key == 0) active @endif">
                            <div class="container">
                                <h1 class="fashion_taital">{{ $Category->category_name }}</h1>
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
                                                        <div class="buy_bt"><a href="#">{{ __('page.Buy Now') }}</a>
                                                        </div>
                                                        <div class="seemore_bt"><a
                                                                href="#">{{ __('page.See More') }}</a>
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
                <a class="carousel-control-prev" href="#{{ $Category->id }}" role="button" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="carousel-control-next" href="#{{ $Category->id }}" role="button" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </div>
    @empty
        <a class="dropdown-item" href="#">{{ trans('layout.There are no sections') }} </a>
    @endforelse
@endsection
