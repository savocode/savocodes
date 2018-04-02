@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
@endsection

@section('JSLibraries')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $moduleProperties['longModuleName'] }}
          <div class="pull-right">
              <a href="{{ backend_url(str_singular($moduleProperties['controller']).'/edit/'. $record->id) }}" type="button" class="btn btn-primary btn-flat">
                  Edit {{ $record->title }}
              </a>
          </div>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="box">
            <div class="box-header">
              <h3 class="box-title">Hospital detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl class="dl-horizontal viewModule">

                <dt>Title</dt>
                <dd>{{ $record->title }}</dd>

                <dt>Number Of Admins</dt>
                <dd>
                    <a href="{{ backend_url($moduleProperties['controller'].'/'.$record->id.'/employees/') }}" target="_blank" title="Hospital Employee" class="btn btn-xs btn-primary">
                        <b>{{ $user->count() }} </b><i class="fa fa-user"></i>
                    </a>
                </dd>

                 <dt>Active Admins</dt>
                <dd>{{ $user->whereIsActive(1)->count() }}</dd>

                <dt>Address</dt>
                <dd>{{ ucfirst($record->address) }}</dd>

                <dt>Location</dt>
                <dd>{{ $record->location }}</dd>

                <dt>Zip Code</dt>
                <dd>{{ $record->zip_code }}</dd>

                <dt>Time Open</dt>
                <dd>{{ $record->getTimeFormatted($record->timing_open) }}</dd>

                <dt>Time Close</dt>
                <dd>{{ $record->getTimeFormatted($record->timing_close) }}</dd>

                <dt>Phone</dt>
                <dd>{{ $record->phone }}</dd>

                <dt>Phone 24/7</dt>
                <dd>{!! $record->is_phone !!} </dd>

                 <dt>Active</dt>
                <dd>{!! $record->active_text_formatted !!} </dd>

                <dt>Description</dt>
                <dd>{{ $record->description }}</dd>

                <dt>Registration Date</dt>
                <dd>{{ $record->created_at->format(constants('back.theme.modules.datetime_format')) }}</dd>

                <dt>Update </dt>
                <dd>{!! $record->is_updated !!}</dd>

              </dl>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        @include( 'backend.layouts.modal' )

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
