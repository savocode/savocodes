@extends( 'backend.layouts.app' )

@section('title', $moduleProperties['longModuleName'])

@section('CSSLibraries')
@endsection

@section('JSLibraries')
    <script src="https://www.gstatic.com/firebasejs/4.2.0/firebase.js"></script>

<script type="text/javascript">
    var config = {
      apiKey: "AIzaSyBfQBYgCO9PFhq7baJfospBSLYwtoq3e74",
      databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
    };
    firebase.initializeApp(config);

    firebase.database().ref('/users/{{ $record->prefix_uid }}').on('value', function(snapshot) {
      var userObject = snapshot.val()

      $('#message-sent').text( userObject.hasOwnProperty('messages_sent') ? userObject['messages_sent'] : 0 )
      $('#message-received').text( userObject.hasOwnProperty('messages_received') ? userObject['messages_received'] : 0 )
    });

    var userRef = firebase.database().ref('/users').on('child_changed', function(snapshot, key) {
      if ( snapshot.key === '{{ $record->prefix_uid }}' ) {
        var userObject = snapshot.val()

        $('#message-sent').text( userObject.hasOwnProperty('messages_sent') ? userObject['messages_sent'] : 0 )
        $('#message-received').text( userObject.hasOwnProperty('messages_received') ? userObject['messages_received'] : 0 )
        $('#credits').text( userObject.hasOwnProperty('credits') ? userObject['credits'] : 0 )
      }
    });
</script>
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
                <dd>{{ $record->full_name }}</dd>

                <dt>Account Type</dt>
                <dd>{{ ucfirst($record->user_role_key_web) }}</dd>

                <dt>Email</dt>
                <dd>{{ $record->email }}</dd>

                <dt>Phone</dt>
                <dd>{{ $record->phone }}</dd>

                <dt>Postal Code</dt>
                <dd>{{ isset($userMeta['postal_code']) ? $userMeta['postal_code'] : '' }}</dd>

                <dt>Address</dt>
                <dd>{{ $record->address }}</dd>

                <dt>City</dt>
                <dd>{{ $record->city_title }}</dd>

                <dt>State</dt>
                <dd>{{ $record->state_title }}</dd>

                <dt>Birth Date</dt>
                <dd>{{ isset($userMeta['birth_date']) ? $userMeta['birth_date'] : '' }}</dd>

                <dt>Gender</dt>
                <dd>{{ isset($userMeta['gender']) ? $userMeta['gender'] : '' }}</dd>

                <dt>School Name</dt>
                <dd>{{ isset($userMeta['school_name']) ? $userMeta['school_name'] : '' }}</dd>

                <dt>Student Organization</dt>
                <dd>{{ isset($userMeta['student_organization']) ? $userMeta['student_organization'] : '' }}</dd>

                <dt>Graduation Year</dt>
                <dd>{{ isset($userMeta['graduation_year']) ? $userMeta['graduation_year'] : '' }}</dd>

                @if ($record->isDriver())
                  <dt>Driving License No</dt>
                  <dd>{{ isset($userMeta['driving_license_no']) ? $userMeta['driving_license_no'] : '' }}</dd>

                  <dt>Vehicle Type</dt>
                  <dd>{{ isset($userMeta['vehicle_type']) ? $userMeta['vehicle_type'] : '' }}</dd>

                  <dt>Insurance No</dt>
                  <dd>{{ isset($userMeta['insurance_no']) ? $userMeta['insurance_no'] : '' }}</dd>

                  @if (isset($userMeta['driver_documents']) && !empty($userMeta['driver_documents']))
                    <dt>Driver Documents</dt>
                    <dd>
                      <ul class="list-unstyled">
                        @foreach ($userMeta['driver_documents'] as $document)
                          <li><a href="{{ $document['absolute_url'] }}" target="_blank">{{ $document['absolute_url'] }}</a></li>
                        @endforeach
                      </ul>
                    </dd>
                  @endif
                @endif

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
