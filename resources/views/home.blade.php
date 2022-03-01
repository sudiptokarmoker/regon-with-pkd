@extends('layouts.app')

@section('page_css')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/multi-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/star-rating.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/krajee-fa/theme.css') }}" media="all" type="text/css"/>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Dashboard</h3>
        </div>
        <div class="section-body" id="app">
            <div class="row">
                <div class="offset-4 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row my-2">
                                <div class="col-12">
                                    @if($errors->any())
                                        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                                    @endif

                                    <form class="border p-4" action="{{ route('check-nip') }}" method="POST">
                                        @csrf
                                        <div class="mb-5">
                                            <label for="nip" class="form-label mb-2">Tax identification number</label>
                                            <input type="number" name="nip" class="form-control" id="nip" value="{{ old('nip') ?? session('nip') }}" placeholder="Enter your NIP">
                                        </div>
                                        <input type="submit" name="create_opinion" class="btn btn-primary" value="Add opinions">
                                        <input type="submit" name="check_company" class="btn btn-primary float-right" value="Check company">
                                    </form>

                                    @if ($company = session('company'))
                                        @include('dashboard.partial.form', ['company' => session('company'), 'nip' => session('nip')])
                                    @endif
                                </div>
                            </div>
                            @include('dashboard.partial.alert.message')
                            @include('dashboard.partial.alert.error')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_js')
    <script src="{{ asset('assets/js/multi-form.js') }}"></script>
    <script src="{{ asset('assets/js/star-rating.min.js') }}"></script>
    <script src="{{ asset('themes/krajee-fa/theme.js') }}" type="text/javascript"></script>
    <script>
        $("#opinion-form").multiStepForm({}).navigateTo(0);
    </script>
@endsection

