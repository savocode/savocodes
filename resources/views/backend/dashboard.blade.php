@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('inlineJS')
@endsection

@section('JSLibraries')

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
                        <a class="dashboard_link" href="{{ isset($stats->total_users)?URL::to('backend/users/index'):URL::to('backend/physicians/index') }}">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total {{ isset($stats->total_users)?'User':'Hospital Physician' }}</div>
                                <div class="huge">{{ isset($stats->total_users)?$stats->total_users: $stats->total_hospital_physician}}</div>
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
                        <a class="dashboard_link" href="{{ isset($stats->total_hospital)?URL::to('backend/hospitals/index'):'' }}">
                            <div class="col-xs-3">
                                <i class="fa fa-medkit fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div>Total Hospital {{ isset($stats->total_hospital)?'':'Employee' }}</div>
                                <div class="huge">{{ isset($stats->total_hospital)?$stats->total_hospital:$stats->total_hospital_employee }}</div>
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
                                <div>Total Verified {{ isset($stats->total_verified_users)?'User':'Hospital Physician' }}</div>
                                <div class="huge">{{ isset($stats->total_verified_users)?$stats->total_verified_users:$stats->total_verified_physician }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($stats->total_physician))
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
        @endif

        {{--<div class="col-lg-4 col-md-6">--}}
            {{--<div class="panel panel-red">--}}
                {{--<div class="panel-heading">--}}
                    {{--<div class="row">--}}
                        {{--<a class="dashboard_link" href="javascript:void(0);">--}}
                            {{--<div class="col-xs-3">--}}
                                {{--<i class="fa fa-user-md fa-5x"></i>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-9 text-right">--}}
                                {{--<div>Total Physician</div>--}}
                                {{--<div class="huge">{{ $stats->total_physician }}</div>--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

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
