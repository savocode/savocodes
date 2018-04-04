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

{{--<script type="text/javascript">--}}
  {{--$(function(){--}}
    {{--appConfig.set('yajrabox.ajax', base_url + '/physicians/data');--}}
    {{--appConfig.set('dt.searching', false);--}}
    {{--appConfig.set('yajrabox.ajax.data', function(data) {--}}
      {{--data.state       = jQuery('select[name=state]').val();--}}
      {{--data.city        = jQuery('select[name=city]').val();--}}
      {{--data.gender      = jQuery('select[name=gender]').val();--}}
   {{--//   data.age         = jQuery('select[name=age]').val();--}}
      {{--data.profession  = jQuery('select[name=profession]').val();--}}
     {{--// data.school_name = jQuery('select[name=school_name]').val();--}}
    {{--});--}}
    {{--appConfig.set('yajrabox.columns', [--}}
        {{--{data: 'profile_picture', orderable: false, searchable: false},--}}
        {{--{data: 'first_name'},--}}
        {{--{data: 'last_name'},--}}
        {{--{data: 'email'},--}}
        {{--{data: 'profession_id'},--}}
        {{--{data: 'created_at'},--}}
        {{--{data: 'active'},--}}
        {{--{data: 'action', orderable: false, searchable: false}--}}
    {{--]);--}}
  {{--})--}}
{{--</script>--}}
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Professions
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
              <h3 class="box-title">All Professions</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">


              <div class="clearfix"></div>


              <div class="clearfix"></div>

              <br>

              <table class="table table-bordered table-striped yajrabox">
                <thead>
                <tr>
                  <th width="80">#</th>
                  <th>Title</th>
                  <th>Active</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
                </thead>
                  <tbody>
                    @foreach($professions as $profession)
                        <tr>
                            <td>{{ $profession->id }}</td>
                            <td>{{ $profession->title }}</td>
                            <td>{!!  $profession->active_text  !!} </td>
                            <td>{{ $profession->created_at->format(constants('back.theme.modules.datetime_format')) }}</td>
                            <td>
                                <a href="#"  title="Edit" data-toggle="modal" data-target="#editModal{{ $profession->id }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        <div id="editModal{{ $profession->id }}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Edit Profession</h4>
                                    </div>
                                    {!! Form::model($profession, ['method' => 'put', 'url' => 'backend/settings/profession/edit/'.$profession->id, 'class' =>'form-inline']) !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('profession', 'Profession') !!}
                                                {!! Form::text('profession', $profession->title, ['class'=> 'form-control', 'required']) !!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('is_active', 'Status') !!}
                                                {!! Form::select('is_active', [1=>'Yes', 0=>'No'], $profession->status, ['class'=> 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>

                                        {!! Form::hidden('id', $profession->id) !!}
                                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                  </tbody>
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
