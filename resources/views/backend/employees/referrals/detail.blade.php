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
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        @include( 'backend.layouts.notification_message' )

        <div class="box">
            <div class="box-header">
              <h3 class="box-title">Referral detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl class="dl-horizontal viewModule">

                <dt>Referred By</dt>
                <dd>{{ $record->doctor->full_name }}</dd>

                <dt>Patient Name</dt>
                <dd>{{ $record->full_name_decrypted }}</dd>

                <dt>Age</dt>
                <dd>{{ $record->age_decrypted }}</dd>

                <dt>Phone</dt>
                <dd>{{ $record->phone_decrypted }}</dd>

                <dt>Diagnosis</dt>
                <dd>{{ ucfirst($record->diagnosis_decrypted) }}</dd>

                <dt>Status</dt>
                <dd>{!! $record->status_text  !!}</dd>

                <dt>Created By</dt>
                <dd>{{ $record->created_at->format(constants('back.theme.modules.datetime_format')) }}</dd>

                <div class="panel panel-info">
                  <div class="panel-heading">Referral History</div>
                  <div class="panel-body">
                      <div class="row">
                        @foreach($history as $h)
                            <div class="col-md-6">
                                <div class="box">
                                    <dt>Created By</dt>
                                    <dd>{{ $h->user->full_name_decrypted }}</dd>

                                    <dt>Reason</dt>
                                    <dd>{{ $h->reason }}</dd>

                                    <dt>Status</dt>
                                    <dd>{!! $h->status_text  !!} </dd>

                                    <dt>Created At</dt>
                                    <dd>{!! $h->created_at->format(constants('back.theme.modules.datetime_format'))  !!} </dd>

                                    @if($h->status > 0)
                                        <dt>Referral {{ $h->status == 1?'Accepted': 'Rejected' }} At</dt>
                                        <dd>{!! Carbon\Carbon::parse($h->updated_at)->format(constants('back.theme.modules.datetime_format'))  !!} </dd>
                                    @else
                                        <dt>Referral Pending Since</dt>
                                        <dd>{!! $h->created_at->format(constants('back.theme.modules.datetime_format'))  !!}</dd>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                      </div>
                  </div>
                </div>

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
