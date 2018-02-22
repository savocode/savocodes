@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('inlineJS')
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
        <div class="col-lg-4 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-purple disabled">
            <div class="inner">
              <h3>{{ intval($user->balance) }}</h3>

              <p>Balance</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-xs-4">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $cardPurchased }}</h3>

              <p>Cards Availed</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection