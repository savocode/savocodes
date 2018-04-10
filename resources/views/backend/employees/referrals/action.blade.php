    <a href="{{ backend_url($moduleProperties['controllerReferral'].'/detail/'.$record->id) }}"  title="Detail" class="btn btn-xs btn-primary">
        <i class="fa fa-info-circle"></i>
    </a>
    @if($record->status == 0)
        <a href="#"  title="Accept" data-toggle="modal" data-target="#acceptModal{{ $record->id }}" class="btn btn-xs btn-success">
            <i class="fa fa-check"></i>
        </a>
        <a href="#"  title="Reject" data-toggle="modal" data-target="#rejectModal{{ $record->id }}" class="btn btn-xs btn-danger">
            <i class="fa fa-times-circle"></i>
        </a>


        <div id="acceptModal{{ $record->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Referral Accepted</h4>
                    </div>
                    {!! Form::open(['method' => 'post', 'url' => 'backend/'.$moduleProperties['controllerReferral'].'/accept/'.$record->id]) !!}
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('reason', 'Reason*') !!}
                                {!! Form::textarea('reason', null, ['class'=>'form-control', 'size' => '88x5', 'required']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('id', $record->id) !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        <div id="rejectModal{{ $record->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Referral Rejected</h4>
                    </div>
                    {!! Form::open(['method' => 'post', 'url' => 'backend/'.$moduleProperties['controllerReferral'].'/reject/'.$record->id]) !!}
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('reason', 'Reason*') !!}
                                {!! Form::textarea('reason', null, ['class'=>'form-control', 'size' => '88x5', 'required']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('id', $record->id) !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

