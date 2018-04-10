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
    appConfig.set('yajrabox.ajax', base_url + '/referrals/data');
    appConfig.set('dt.searching', false);
    appConfig.set('yajrabox.ajax.data', function(data) {
      data.diagnosis       = jQuery('#diagnosis option:selected').text();
      data.age             = jQuery('#age option:selected').text();
      data.status          = jQuery('#status option:selected').val();
    });
    appConfig.set('yajrabox.columns', [
        {data: 'referred_by', orderable: false, searchable: false},
        {data: 'first_name'},
        {data: 'last_name'},
        {data: 'age'},
        {data: 'diagnosis'},
        {data: 'created_at'},
        {data: 'status'},
        {data: 'action', orderable: false, searchable: false}
    ]);

    // $('#diagnosis').on('change', function(){
    //     console.log(jQuery('#diagnosis option:selected').text());
    // });
  })
</script>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ $hospital->title  }}
      <div class="pull-right">
        {{--<a href="{{ backend_url($moduleProperties['controller'].'/create/user') }}" type="button" class="btn btn-primary btn-flat">New Physician</a>--}}
      </div>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="box">
            <div class="box-header">
              <h3 class="box-title">All Referrals</h3>
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
                          <div class="col-sm-4">
                              <label>Status</label>
                              {!! Form::select('status', $status, null, ['class' => 'form-control chosen', 'id'=>'status']) !!}
                          </div>
                        <div class="col-sm-4">
                            <label>Diagnosis</label>
                            {!! Form::select('diagnosis', $diagnosis, null, ['class' => 'form-control chosen', 'id'=>'diagnosis']) !!}
                        </div>
                        <div class="col-sm-4">
                            <label>Age</label>
                            {!! Form::select('age', $age, null, ['class' => 'form-control chosen', 'id'=>'age']) !!}
                        </div>
                      </div>
                  </form>
              </div>

              <div class="clearfix"></div>

              <br>

              <table class="table table-bordered table-striped yajrabox">
                <thead>
                <tr>
                  <th width="80">Referred By</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Age</th>
                  <th>Diagnosis</th>
                  <th width="130">Date Registered</th>
                  <th width="50">Status</th>
                  <th width="70">Action</th>
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
