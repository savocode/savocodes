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
    appConfig.set('yajrabox.ajax', base_url + '/users/data');
    appConfig.set('dt.searching', false);
    appConfig.set('dt.order', [5, 'desc']);
    appConfig.set('yajrabox.ajax.data', function(data) {
      data.state       = jQuery('select[name=state]').val();
      data.city        = jQuery('select[name=city]').val();
      data.gender      = jQuery('select[name=gender]').val();
      data.profession  = jQuery('select[name=profession]').val();
    });
    appConfig.set('yajrabox.columns', [
        {data: 'profile_picture',   orderable: false, searchable: false},
        {data: 'first_name',        orderable: false, searchable: false},
        {data: 'last_name',         orderable: false, searchable: false},
        {data: 'email',             orderable: false, searchable: false},
        {data: 'profession_id',     orderable: false, searchable: false},
        {data: 'created_at',        orderable: true,  searchable: false},
        {data: 'active',            orderable: false, searchable: false},
        {data: 'action',            orderable: false, searchable: false}
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
        {{--<a href="{{ backend_url($moduleProperties['controller'].'/create/user') }}" type="button" class="btn btn-primary btn-flat">New Physician</a>--}}
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
                        <div class="col-sm-3">
                            <label>State</label>
                            {!! Form::select('state', $states, null, ['class' => 'form-control chosen', 'id' => 'ddl_states']) !!}
                        </div>
                        <div class="col-sm-3">
                            <label>City</label>
                            {!! Form::select('city', $cities, null, ['class' => 'form-control chosen', 'id' => 'ddl_cities']) !!}
                        </div>
                          <div class="col-sm-3">
                              <label>Gender</label>
                              {!! Form::select('gender', $genders, null, ['class' => 'form-control chosen']) !!}
                          </div>
                        <div class="col-sm-3">
                            <label>Professions</label>
                            {!! Form::select('profession', $professions, null, ['class' => 'form-control chosen']) !!}
                        </div>
                      </div>
                  </form>
              </div>

              <div class="clearfix"></div>

              <br>

              <table class="table table-bordered table-striped yajrabox">
                <thead>
                <tr>
                  <th width="80">Picture</th>
                  <th width="100">First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th width="50">Profession</th>
                  <th width="130">Date Registered</th>
                  <th width="40">Status</th>
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
    <script type="text/javascript">
        var url='{!! URL::to('/') !!}';

        // Fetch cities
        $('#ddl_states').on('change', function() {
            $.getJSON(url + '/backend/cities/' + this.value, function(data) {
                var options = $("#ddl_cities");
                options.html('');
                  options.append($('<option></option>').val('').html('Select City'));
                $.each(data, function(key, val) {
                    options.append($('<option></option>').val(key).html(val));
                });

                options.trigger('chosen:updated')
            });
        })
    </script>
@endsection
