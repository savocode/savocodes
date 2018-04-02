                        <a href="{{ backend_url($moduleProperties['controller'].'/detail/'.$record->id) }}" target="_blank" title="Detail" class="btn btn-xs btn-primary">
                            <i class="fa fa-info-circle"></i>
                        </a>

                        @if ($record->is_active == 1)
                            <a href="{{ backend_url($moduleProperties['controller'].'/block/'.$record->id) }}" title="Block" class="btn btn-xs btn-primary block">
                                <i class="fa fa-ban"></i>
                            </a>
                        @else
                            <a href="{{ backend_url($moduleProperties['controller'].'/unblock/'.$record->id) }}" title="Unblock" class="btn btn-xs btn-primary block">
                                <i class="fa fa-unlock"></i>
                            </a>
                        @endif

                        @if ( !in_array($record->id, array_get($moduleProperties, 'undeleteable', [])) )
                            <a class="btn btn-xs btn-danger delete" type="button" title="Delete" data-toggle="modal" data-target="#deleteModal{{ $record->id }}">
                                <i class="fa fa-trash"></i>
                            </a>

                            <div id="deleteModal{{ $record->id }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Confirm Delete</h4>
                                </div>
                                <div class="modal-body">
                                <p>Are you sure you want to delete this item?</p>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                {!! Form::model($record, ['method' => 'delete', 'url' => 'backend/'.$moduleProperties['controller'].'/'.$record->id, 'class' =>'form-inline form-delete']) !!}
                                {!! Form::hidden('id', $record->id) !!}
                                {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                                {!! Form::close() !!}
                                </div>
                                </div>
                                </div>
                            </div>
                        @endif
