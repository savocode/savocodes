@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
    <!-- DataTables CSS -->
    {{--<link href="{{ backend_asset('plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">--}}
    <link href="{{ backend_asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('plugins/colorbox/colorbox.css') }}" rel="stylesheet">
    <link href="{{ backend_asset('plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('JSLibraries')
<!-- DataTables JavaScript -->
{{--<script src="{{ backend_asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ backend_asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>--}}
<script src="{{ backend_asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="{{ backend_asset('plugins/colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ backend_asset('plugins/chosen/chosen.jquery.min.js') }}"></script>

<script type="text/javascript">
  // $(function(){
  //   appConfig.set('yajrabox.ajax', base_url + '/physicians/data');
  //   appConfig.set('dt.searching', false);
  //   appConfig.set('yajrabox.ajax.data', function(data) {
  //     data.state       = jQuery('select[name=state]').val();
  //     data.city        = jQuery('select[name=city]').val();
  //     data.gender      = jQuery('select[name=gender]').val();
  //  //   data.age         = jQuery('select[name=age]').val();
  //     data.profession  = jQuery('select[name=profession]').val();
  //    // data.school_name = jQuery('select[name=school_name]').val();
  //   });
  //   appConfig.set('yajrabox.columns', [
  //       {data: 'profile_picture', orderable: false, searchable: false},
  //       {data: 'first_name'},
  //       {data: 'last_name'},
  //       {data: 'email'},
  //       {data: 'profession_id'},
  //       {data: 'created_at'},
  //       {data: 'active'},
  //       {data: 'action', orderable: false, searchable: false}
  //   ]);
  // })

  $('.some-textarea').wysihtml5();
</script>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Criteria
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
              <h3 class="box-title">Criteria</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">


              <div class="clearfix"></div>


              <div class="clearfix"></div>

              <br>
                <?php $i=0?>
              {!! Form::open(['method' => 'POST', 'url' => 'backend/settings/criteria/edit']) !!}
                @foreach($criteria as $c)
                    <div class="panel panel-info">
                        <div class="panel-heading">{{ $c['title'] }}</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    {!! Form::label('title', 'Title') !!}
                                    {!! Form::text('title[]', $c['title'], ['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                @foreach($c['sub_criteria'] as $sub)
                                    <div class="col-md-12 form-group">
                                        <div class="box-body">
                                            {!! Form::label('name', 'Name') !!}
                                            {!! Form::text("name[".$i."][]", $sub['name'], ['class'=>'form-control']) !!}
                                            <br>
                                            {!! Form::label('body', 'Body') !!}
                                            {!! Form::textarea("body[".$i."][]", $sub['body'], ['class'=>'form-control some-textarea', 'id'=> '']) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <?php $i++?>
                @endforeach
                <div class="pull-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat']) !!}
                </div>
             {!! Form::close() !!}

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
