<div class="row">
    <div class="col-md-12 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'School Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="pull-left">
    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat']) !!}
    <a href="{{ backend_url($moduleProperties['controller']) }}" type="button" class="btn btn-default btn-flat">Cancel</a>
</div>