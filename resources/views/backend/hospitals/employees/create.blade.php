@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
    <link href="{{ backend_asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
    <script src="{{ backend_asset('plugins/select2/select2.full.min.js') }}"></script>

    {{--<script>--}}
      {{--$('.select2').select();--}}
    {{--</script>--}}


@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $hospital->title }}
      <small>Add new employee</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">  
                <h3 class="box-title">Create Mode</h3>
              </div>
              <div class="box-body">
                  {!! Form::open(['url' => 'backend/'.$moduleProperties['controller'].'/'.$hospital->id.'/employee/create', 'files' => 'true']) !!}
                    @include( 'backend/' . $moduleProperties['viewDir'] . '.employees.form' )
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