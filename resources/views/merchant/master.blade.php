<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('merchant-assets') }}/" data-template="vertical-menu-template-free">
@include('merchant.partials.head')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @extends('merchant.partials.sidebar')
            @extends('merchant.partials.navbar')
            <div class="layout-page">
                @yield('content')
                @extends('merchant.partials.footer')
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('merchant.partials.scripts')
</body>

</html>
