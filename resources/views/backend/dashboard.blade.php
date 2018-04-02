@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('inlineJS')
@endsection

@section('JSLibraries')
{{--     <script src="https://www.gstatic.com/firebasejs/4.2.0/firebase.js"></script>

<script type="text/javascript">
    var config = {
      apiKey: "AIzaSyBfQBYgCO9PFhq7baJfospBSLYwtoq3e74",
      databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
    };
    firebase.initializeApp(config);

    firebase.database().ref('/system').on('value', function(snapshot) {
      var userObject = snapshot.val()

      $('#total_messages').text( userObject.hasOwnProperty('total_messages') ? userObject['total_messages'] : 0 )
    });

    var userRef = firebase.database().ref('/system').on('child_changed', function(snapshot, key) {
        var userObject = snapshot.val()

        $('#total_messages').text( userObject.hasOwnProperty('total_messages') ? userObject['total_messages'] : 0 )
    });
</script> --}}
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Dashboard</h1>
      <ol class="breadcrumb">
        <li class="active"><a href=""><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="row">

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="{{ URL::to('backend/users/index') }}">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Users</div>
                                <div class="huge">{{ $stats->total_users }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-medkit fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Hospital</div>
                                <div class="huge">{{ $stats->total_hospital }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-check fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Verified Users</div>
                                <div class="huge">{{ $stats->total_verified_users }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-user-md fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Physician</div>
                                <div class="huge">{{ $stats->total_physician }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-user-md fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Physician</div>
                                <div class="huge">{{ $stats->total_physician }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="{{ URL::to('backend/messages') }}">
                            <div class="col-xs-3">
                                <i class="fa fa-envelope-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Messages Sent/Received</div>
                                <div class="huge" id="total_messages">{{ $stats->messages }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="{{ URL::to('backend/messages') }}">
                            <div class="col-xs-3">
                                <i class="fa fa-envelope fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Unrespond Messages</div>
                                <div class="huge">{{ $stats->unresponded_messages }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="{{ URL::to('backend/reports') }}">
                            <div class="col-xs-3">
                                <i class="fa fa-dollar fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>ChitChat Revenue Earned</div>
                                <div class="huge">{{ prefixCurrency($stats->commission_earned) }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-dollar fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>ChitChat Credit Revenue</div>
                                <div class="huge">{{ prefixCurrency($stats->credits_purchased->get('amountTotal', 0) * 75 / 100) }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <a class="dashboard_link" href="javascript:void(0);">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-basket fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Credits Purchased</div>
                                <div class="huge">{{ $stats->credits_purchased->get('creditsTotal', 0) }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
