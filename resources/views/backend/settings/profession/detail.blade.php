@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
@endsection

@section('JSLibraries')
    {{--<script src="https://www.gstatic.com/firebasejs/4.2.0/firebase.js"></script>--}}

{{--<script type="text/javascript">--}}
    {{--var config = {--}}
      {{--apiKey: "AIzaSyBfQBYgCO9PFhq7baJfospBSLYwtoq3e74",--}}
      {{--databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"--}}
    {{--};--}}
    {{--firebase.initializeApp(config);--}}

    {{--firebase.database().ref('/users/{{ $record->prefix_uid }}').on('value', function(snapshot) {--}}
      {{--var userObject = snapshot.val()--}}

      {{--$('#message-sent').text( userObject.hasOwnProperty('messages_sent') ? userObject['messages_sent'] : 0 )--}}
      {{--$('#message-received').text( userObject.hasOwnProperty('messages_received') ? userObject['messages_received'] : 0 )--}}
    {{--});--}}

    {{--var userRef = firebase.database().ref('/users').on('child_changed', function(snapshot, key) {--}}
      {{--if ( snapshot.key === '{{ $record->prefix_uid }}' ) {--}}
        {{--var userObject = snapshot.val()--}}

        {{--$('#message-sent').text( userObject.hasOwnProperty('messages_sent') ? userObject['messages_sent'] : 0 )--}}
        {{--$('#message-received').text( userObject.hasOwnProperty('messages_received') ? userObject['messages_received'] : 0 )--}}
        {{--$('#credits').text( userObject.hasOwnProperty('credits') ? userObject['credits'] : 0 )--}}
      {{--}--}}
    {{--});--}}
{{--</script>--}}
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
              <h3 class="box-title">User detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl class="dl-horizontal viewModule">

                <dt>Full Name</dt>
                <dd>{{ $record->full_name_decrypted }}</dd>

                <dt>Account Type</dt>
                <dd>{{ ucfirst($record->user_role_key_web) }}</dd>

                <dt>Email</dt>
                <dd>{{ $record->email_decrypted }}</dd>

                <dt>Phone</dt>
                <dd>{{ $record->phone_decrypted }}</dd>

                <dt>Postal Code</dt>
                <dd>{{ isset($userMeta['postal_code']) ? $userMeta['postal_code'] : '' }}</dd>

                <dt>Address</dt>
                <dd>{{ $record->address }}</dd>

                <dt>City</dt>
                <dd>{{ $record->city_title }}</dd>

                <dt>State</dt>
                <dd>{{ $record->state_title }}</dd>

                <dt>Birth Date</dt>
                <dd>{{ isset($userMeta['birth_date']) ? Carbon\Carbon::parse($userMeta['birth_date'])->format(constants('back.theme.modules.date_format')) : '' }}</dd>

                <dt>Gender</dt>
                <dd>{{ isset($userMeta['gender']) ? $userMeta['gender'] : '' }}</dd>


                <dt>Email Verified</dt>
                <dd>{{ $record->email_verification == '1' ? 'Yes' : 'Pending' }}</dd>

                <dt>Display Picture</dt>
                <dd>{!! Html::image($record->profile_picture_auto, null, ['class' => 'img-responsive', 'style' => 'max-width: 100px;max-height: 100px']) !!}</dd>

                <br />
                <dd>
                      <a href="javascript:void(0)" onclick="window.history.go(-1);" class="btn btn-primary" type="button">Go back</a>
                </dd>

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
