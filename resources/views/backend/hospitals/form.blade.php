<div class="row">
    <div class="col-sm-12 form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        {!! Form::label('type', 'Type*') !!}
        {!! Form::select('type', ['hospital' => 'Hospitals', 'health_care' => 'Health Care Center'], old('type'), ['class' => 'form-control']) !!}
        @if ($errors->has('type'))
            <span class="help-block">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        {!! Form::label('title', 'Title*') !!}
        {!! Form::text('title', old('title'), ['class' => 'form-control']) !!}
        @if ($errors->has('title'))
            <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>

    <div class="col-sm-6 form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone', old('phone'), ['class' => 'form-control']) !!}
        @if ($errors->has('phone'))
            <span class="help-block">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {!! Form::label('address', 'Address') !!}
        {!! Form::text('address', old('address'), ['class' => 'form-control']) !!}
        @if ($errors->has('address'))
            <span class="help-block">
                <strong>{{ $errors->first('address') }}</strong>
            </span>
        @endif
    </div>

    <div class="col-sm-6 form-group{{ $errors->has('location') ? ' has-error' : '' }}">
        {!! Form::label('location', 'Location') !!}
        {!! Form::text('location', old('location'), ['class' => 'form-control']) !!}
        <span class="help-block">
        @if ($errors->has('location'))
            <strong>{{ $errors->first('location') }}</strong><br />
        @endif
        </span>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
        {!! Form::label('zip_code', 'Zip Code*') !!}
        {!! Form::text('zip_code', old('zip_code'), ['class' => 'form-control']) !!}
        @if ($errors->has('zip_code'))
            <span class="help-block">
                <strong>{{ $errors->first('zip_code') }}</strong>
            </span>
        @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('is_24_7_phone') ? ' has-error' : '' }}">
        {!! Form::label('is_24_7_phone', 'Phone 24/7*') !!}
        {!! Form::select('is_24_7_phone', [1=> "Yes", 0=> 'No'], isset($record->is_24_7_phone)?intval($record->is_24_7_phone):old('is_24_7_phone'), ['class' => 'form-control']) !!}
        @if ($errors->has('is_24_7_phone'))
            <span class="help-block">
                <strong>{{ $errors->first('is_24_7_phone') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('timing_open') ? ' has-error' : '' }}">
        {!! Form::label('timing_open', 'Timing Open*') !!}
        {!! Form::text('timing_open', old('timing_open'), ['class' => 'form-control timepicker', 'readonly' => 'true']) !!}
        @if ($errors->has('timing_open'))
            <span class="help-block">
                <strong>{{ $errors->first('timing_open') }}</strong>
            </span>
        @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('timing_close') ? ' has-error' : '' }}">
        {!! Form::label('timing_close', 'Timing Close*') !!}
        {!! Form::text('timing_close', old('timing_close'), ['class' => 'form-control timepicker', 'readonly' => 'true']) !!}
        @if ($errors->has('timing_close'))
            <span class="help-block">
                <strong>{{ $errors->first('timing_close') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12 form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        {!! Form::label('description', 'Description*') !!}
        {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'size' => '30x10']) !!}
        @if ($errors->has('description'))
            <span class="help-block">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="pull-left">
    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat']) !!}
    <a href="{{ backend_url($moduleProperties['controller']) }}" type="button" class="btn btn-default btn-flat">Cancel</a>
</div>

@section('inlineJS')
@endsection
