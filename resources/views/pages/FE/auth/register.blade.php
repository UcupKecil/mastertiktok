@extends('layouts.FE.page')
@section('content')
    <section class="page-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="banner-content text-center">
                        <h1>Register</h1>
                        <p>
                            <a href="{{ url('/') }}">Home</a>
                            <span> > </span>Register
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <form action="{{ url('/auth/register') }}" method="post" id="form">
        @csrf
        <section class="login-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 login-form-wrap">
                        @error('error')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @enderror
                        <div class="form-group">
                            <label for="">Kelas yang dipesan</label>
                            <input type="text" class="form-control" value="{{ $course->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <input type="text" class="form-control" value="Rp. {{ number_format($course->price) }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" placeholder="Your name" class="form-control" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger" style="display: block;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" placeholder="Email Address" class="form-control" name="email"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger" style="display: block;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" placeholder="Password" class="form-control" name="password" required>
                            @error('password')
                                <div class="text-danger" style="display: block;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" placeholder="Phone" class="form-control" name="phone" required>
                            @error('phone')
                                <div class="text-danger" style="display: block;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="uid">Kode referral</label>
                            @if (Session::get('referral'))
                                <input type="hidden" class="form-control" name="uid"
                                    value="{{ Session::get('referral') }}">
                                <input type="text" class="form-control" value="{{ Session::get('referral') }}" readonly>
                            @else
                                <input type="text" placeholder="Kode referral" class="form-control" name="uid">
                            @endif
                        </div>
                        <div class="login-btn text-center">
                            <a href="javascript:void(0)" onclick='document.getElementById("form").submit();''>Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
<!--end footer-->
