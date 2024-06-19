    <!-- footer section start -->
    <div class="footer_section layout_padding">
        <div class="container">
            <div class="footer_logo"><a href="{{ route('home.index') }}"><img src="{{ asset($Setting['logo']) }}"
                        class="img-fluid" style="max-width: 100px;"></a></div>
            <div class="input_bt">
                <input type="text" class="mail_bt" placeholder="{{ __("layout.Your Email") }}" name='Your Email'>
                <span class="subscribe_bt" id="basic-addon2"><a href="#">{{ __('layout.Subscribe') }}</a></span>
            </div>
            <div class="footer_menu">
                <ul>
                    <li><a href="#">{{ trans('layout.Best Sellers') }}</a></li>
                    <li><a href="#">{{ trans('layout.Gift Ideas') }}</a></li>
                    <li><a href="#">{{ trans('layout.New Releases') }}</a></li>
                    <li><a href="#">{{ trans('layout.Todays Deals') }}</a></li>
                    <li><a href="#">{{ trans('layout.Customer Service') }}</a></li>
                </ul>
            </div>
            <div class="location_main">{{ __('layout.Complaints and Inquiries Number') }} : <a
                    href="tel:{{ $Setting['phone_number'] ?? '' }}">{{ $Setting['phone_number'] ?? '' }}</a></div>
        </div>
    </div>
    <!-- footer section end -->
    <!-- copyright section start -->
    <div class="copyright_section">
        <div class="container">
            <p class="copyright_text">© 2024 {{__('layout.All Rights Reserved by')}} <a
                    href="https://osama-zayed.github.io/portfolio/">{{__('layout.osama zayed')}}
                </a></p>
        </div>
    </div>
    <!-- copyright section end -->
