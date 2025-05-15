@extends('merchant.auth.master')
@section('title', 'Verify Email')
@section('content')


    <!-- Register -->
    <div class="card">
        <div class="card-body">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>
        
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif
        
            <div class="flex items-center justify-between mt-4">
                <form method="POST" action="{{ route('merchant.verification.send') }}">
                    @csrf
        
                    <div>
                        <button class="px-4 py-2 font-bold text-black bg-blue-500 rounded hover:bg-blue-700 ">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>
        
                <form method="POST" action="{{ route("merchant.logout") }}">
                    @csrf
        
                    <button type="submit" class="px-4 py-2 mt-4 font-bold text-black bg-red-500 rounded hover:bg-red-700">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- /Register -->

@endsection
