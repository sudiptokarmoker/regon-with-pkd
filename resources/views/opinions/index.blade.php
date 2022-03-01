@extends('layouts.app')
@section('title')
     @lang('models/opinions.plural')
@endsection

@section('page_css')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/star-rating.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/krajee-fa/theme.css') }}" media="all" type="text/css"/>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/opinions.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('home')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('opinions.table')
            </div>
       </div>
   </div>

        @include('stisla-templates::common.paginate', ['records' => $opinions])

    </section>
@endsection

@section('page_js')
    <script src="{{ asset('assets/js/star-rating.min.js') }}"></script>
    <script src="{{ asset('themes/krajee-fa/theme.js') }}" type="text/javascript"></script>
    <script>
        $("#opinion-form").multiStepForm(
			{
				// defaultStep:0,
				beforeSubmit : function(form, submit){
					console.log("called before submiting the form");
					console.log(form);
					console.log(submit);
				},
				/* validations:val, */
			}
			).navigateTo(0);
    </script>
@endsection



