@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('plugins/colorbox/colorbox.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
<!-- DataTables JavaScript -->
<script src="{{ backend_asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ backend_asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ backend_asset('plugins/colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ backend_asset('plugins/chosen/chosen.jquery.min.js') }}"></script>

<script type="text/javascript">
  $(function(){

    appConfig.set('dt.order', [6, 'desc']);
    appConfig.set('yajrabox.ajax', base_url + '/hospitals/data');
      appConfig.set('yajrabox.ajax.data', function(data) {
          data.type       = jQuery('select[name=type]').val();
      });
    appConfig.set('yajrabox.columns', [
        {data: 'title',         orderable: false, searchable: true},
        {data: 'timing_open',   orderable: false, searchable: false},
        {data: 'timing_close',  orderable: false, searchable: false},
        {data: 'type',          orderable: false, searchable: false},
        {data: 'is_24_7_phone', orderable: false, searchable: false},
        {data: 'phone',         orderable: false, searchable: false},
        {data: 'created_at',    orderable: true,  searchable: false},
        {data: 'active',        orderable: false, searchable: false},
        {data: 'action',        orderable: false, searchable: false}
    ]);
  })
</script>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $moduleProperties['longModuleName'] }}
      <div class="pull-right">
        <a href="{{ backend_url($moduleProperties['controller'].'/create') }}" type="button" class="btn btn-primary btn-flat">New Hospital</a>
      </div>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="box">
            <div class="box-header">
              <h3 class="box-title">All {{ str_plural($moduleProperties['longModuleName']) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <div class="filter-tools">
                    <div class="btn-group button-container pull-right">
                        <button type="button" class="btn btn-flat btn-default clear-data">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                            Clear Filter
                        </button>
                        <button type="button" class="btn btn-flat btn-default filter-data">
                            <i class="fa fa-filter" aria-hidden="true"></i> Filter
                        </button>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="grid-filter">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Type</label>
                                {!! Form::select('type', $type, null, ['class' => 'form-control chosen', 'id' => 'type']) !!}
                            </div>
                        </div>
                    </form>
                </div>

              <div class="clearfix"></div>
              <br>
              <table class="table table-bordered table-striped yajrabox">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Timing Open</th>
                    <th>Timing Close</th>
                    <th>Type</th>
                    <th width="15">Phone 24/7</th>
                    <th>Phone</th>
                    <th width="130">Date Registered</th>
                    <th width="50">Status</th>
                    <th width="120">Action</th>
                </tr>
                </thead>
              </table>
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

@section('inlineJS')

@endsection
