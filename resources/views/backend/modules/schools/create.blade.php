@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
  <link href="{{ backend_asset('plugins/icon-picker/icon-picker.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
  <script src="{{ backend_asset('plugins/icon-picker/icon-picker.min.js') }}"></script>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $moduleProperties['longModuleName'] }}
      <small>Add new {{ str_singular($moduleProperties['shortModuleName']) }}</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-body">
                  {!! Form::open(['method' => 'POST', 'url' => 'backend/'.$moduleProperties['controller']]) !!}
                    @include( 'backend/' . $moduleProperties['viewDir'] . '.form' )
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