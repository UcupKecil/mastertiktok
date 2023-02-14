@extends('layouts.FE.page')
@section('content')
    <section class="page-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="banner-content text-center">
                        <h1>Dashboard</h1>
                        <p>
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="course-archive">
        <div id="accordion">
            <div class="card">
                <div class="card-header two">
                    <a class="card-link" data-toggle="collapse" href="#collapseOne" aria-expanded="true">Link Referral
                        Saya</a>
                </div>
                <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                    <div class="card-body current">
                        <a href="javascript:void(0)" onclick="copy()"
                            id="myReferral">{{ url('/member/aff/' . $user->uid) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    @include($js)
@endpush
