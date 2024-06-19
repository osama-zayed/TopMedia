 <!-- banner bg main start -->
 <div class="banner_bg_main">
     <!-- header top section start -->
     <div class="container">
         <div class="header_section_top">
             <div class="row">
                 <div class="col-sm-12">
                     <div class="custom_menu">
                         <ul>
                             <li><a href="#">{{ trans('layout.Best Sellers') }}</a></li>
                             <li><a href="#">{{ trans('layout.Gift Ideas') }}</a></li>
                             <li><a href="#">{{ trans('layout.New Releases') }}</a></li>
                             <li><a href="#">{{ trans('layout.Todays Deals') }}</a></li>
                             <li><a href="#">{{ trans('layout.Customer Service') }}</a></li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- header top section start -->
     <!-- logo section start -->
     <div class="logo_section">
         <div class="container">
             <div class="row">
                 <div class="col-12">
                     <div class="logo d-flex justify-content-center">
                         <a href="{{ route('home.index') }}">
                             <img src="{{ asset($Setting['logo']) }}" class="img-fluid"
                                 style="max-width: 100px;">
                         </a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- logo section end -->
     <!-- header section start -->
     <div class="header_section">
         <div class="container">
             <div class="containt_main">
                 <div id="mySidenav" class="sidenav">
                     <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                     <a href="{{ route('home.index') }}">{{ trans('layout.Home') }}</a>
                     @forelse (\App\Models\Category::get() as $data)
                         <a href="{{ route('Category.index', ['Category_id' => $data->id]) }}">{{ $data->category_name }}
                         </a>
                     @empty
                         <a href="">{{ trans('layout.There are no sections') }} </a>
                     @endforelse
                 </div>
                 <span class="toggle_icon" onclick="openNav()"><img
                         src="{{ asset('asset/images/toggle-icon.png') }}"></span>
                 <div class="dropdown">
                     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                         data-toggle="dropdown" aria-haspopup="true"
                         aria-expanded="false">{{ trans('layout.All Category') }}
                     </button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         @forelse (\App\Models\Category::take(10)->get()   as $data)
                             <a class="dropdown-item"
                                 href="{{ route('Category.index', ['Category_id' => $data->id]) }}">{{ $data->category_name }}
                             </a>
                         @empty
                             <a class="dropdown-item" href="#">{{ trans('layout.There are no sections') }} </a>
                         @endforelse
                     </div>
                 </div>
                 <div class="main">
                     <!-- Another variation with a button -->
                     <div class="input-group">
                         <input type="text" class="form-control"
                             placeholder="{{ trans('layout.Search this blog') }}">
                         <div class="input-group-append">
                             <button class="btn btn-secondary" type="button"
                                 style="background-color: #f26522; border-color:#f26522 ">
                                 <i class="fa fa-search"></i>
                             </button>
                         </div>
                     </div>
                 </div>
                 <div class="header_box">
                     <div class="lang_box ">
                         <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), null, [], true) }}"
                             title="Language" class="nav-link" data-toggle="dropdown" aria-expanded="true">
                             <img src="{{ asset('asset/images/flag-' . LaravelLocalization::getCurrentLocale() . '.png') }}"
                                 alt="flag" class="mr-2 "
                                 title=" {{ LaravelLocalization::getCurrentLocaleNative() }}">
                             {{ LaravelLocalization::getCurrentLocaleNative() }} <i class="fa fa-angle-down ml-2"
                                 aria-hidden="true"></i>
                         </a>
                         <ul class="dropdown-menu" data-aos="fade-down" data-aos-duration="400" data-aos-delay="50">
                             @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                 @if ($localeCode != LaravelLocalization::getCurrentLocale())
                                     <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}"
                                         href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                         <img src="{{ asset('asset/images/flag-' . $localeCode . '.png') }}"
                                             class="mr-2" alt="flag">
                                         {{ $properties['native'] }}
                                     </a>
                                 @endif
                             @endforeach
                         </ul>
                     </div>
                     <div class="login_menu">
                         <ul>
                             <li><a href="#">
                                     <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                     <span class="padding_10">{{ trans('layout.Cart') }}</span></a>
                             </li>
                             <li><a href="#">
                                     <i class="fa fa-user" aria-hidden="true"></i>
                                     <span class="padding_10">{{ trans('layout.Cart') }}</span></a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- header section end -->
     <!-- banner section start -->
     <div class="banner_section layout_padding">
         <div class="container">
             <div id="my_slider" class="carousel slide" data-ride="carousel">
                 <div class="carousel-inner">
                     <div class="carousel-item active">
                         <div class="row">
                             <div class="col-sm-12">
                                 <h1 class="banner_taital">{{ trans('layout.Get Start') }}
                                     <br>{{ trans('layout.Your favriot shoping') }}
                                 </h1>
                                 <div class="buynow_bt"><a href="#">{{ trans('layout.Buy Now') }}</a></div>
                             </div>
                         </div>
                     </div>
                     <div class="carousel-item">
                         <div class="row">
                             <div class="col-sm-12">
                                 <h1 class="banner_taital">{{ trans('layout.Get Start') }}
                                     <br>{{ trans('layout.Your favriot shoping') }}
                                 </h1>
                                 <div class="buynow_bt"><a href="#">{{ trans('layout.Buy Now') }}</a></div>
                             </div>
                         </div>
                     </div>
                     <div class="carousel-item">
                         <div class="row">
                             <div class="col-sm-12">
                                 <h1 class="banner_taital">{{ trans('layout.Get Start') }}
                                     <br>{{ trans('layout.Your favriot shoping') }}
                                 </h1>
                                 <div class="buynow_bt"><a href="#">{{ trans('layout.Buy Now') }}</a></div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <a class="carousel-control-prev" href="#my_slider" role="button" data-slide="prev">
                     <i class="fa fa-angle-left"></i>
                 </a>
                 <a class="carousel-control-next" href="#my_slider" role="button" data-slide="next">
                     <i class="fa fa-angle-right"></i>
                 </a>
             </div>
         </div>
     </div>
     <!-- banner section end -->
 </div>
 <!-- banner bg main end -->
