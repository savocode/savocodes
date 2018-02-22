@extends( 'backend.layouts.app' )

@section('title', 'Edit Settings')

@section('CSSLibraries')
    <link href="{{ backend_asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('plugins/colorbox/colorbox.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
    <script src="{{ backend_asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ backend_asset('plugins/colorbox/jquery.colorbox-min.js') }}"></script>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Edit Settings
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Edit Mode</h3>
              </div>
              <div class="box-body">
                  {!! Form::open(['method' => 'POST', 'url' => route('backend.settings')]) !!}
                    <div class="row">
                        <div class="col-sm-6 form-group{{ $errors->has('rate_per_mile') ? ' has-error' : '' }}">
                            {!! Form::label('rate_per_mile', 'Rate Per Mile') !!}
                            {!! Form::text('rate_per_mile', $ratePerMile, ['class' => 'form-control']) !!}
                            @if ($errors->has('rate_per_mile'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('rate_per_mile') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="pull-left">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-flat']) !!}
                        <a href="{{ backend_url('dashboard') }}" type="button" class="btn btn-default btn-flat">Cancel</a>
                    </div>

                  {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection
