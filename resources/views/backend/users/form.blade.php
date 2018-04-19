<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        {!! Form::label('first_name', 'First Name') !!}
        {!! Form::text('first_name', old('first_name'), ['class' => 'form-control']) !!}
        @if ($errors->has('first_name'))
            <span class="help-block">
                <strong>{{ $errors->first('first_name') }}</strong>
            </span>
        @endif
    </div>
    
    <div class="col-sm-6 form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        {!! Form::label('last_name', 'Last Name') !!}
        {!! Form::text('last_name', old('last_name'), ['class' => 'form-control']) !!}
        @if ($errors->has('last_name'))
            <span class="help-block">
                <strong>{{ $errors->first('last_name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email') !!}
        {!! Form::email('email', old('email'), ['class' => 'form-control']) !!}
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    
    <div class="col-sm-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', ['class' => 'form-control']) !!}
        <span class="help-block">
        @if ($errors->has('password'))
            <strong>{{ $errors->first('password') }}</strong><br />
        @endif
        @if ( isset($record) )
            <strong>Note:</strong> Keep it blank if you do not want to change it.
        @endif
        </span>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        {!! Form::label('phone', 'Phone (e.g : +14155552671)') !!}
        {!! Form::text('phone', old('phone'), ['class' => 'form-control']) !!}
        @if ($errors->has('phone'))
            <span class="help-block">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-md-6 form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        {!! Form::label('address', 'Address') !!}
        {!! Form::text('address', old('address'), ['class' => 'form-control']) !!}
        @if ($errors->has('address'))
            <span class="help-block">
                <strong>{{ $errors->first('address') }}</strong>
            </span>
        @endif
    </div>

</div>

<div class="row">
    <div class="col-sm-6 form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        {!! Form::label('state', 'State') !!}
        {!! Form::select('state', $states, old('state'), ['class' => 'form-control select2', 'id' => 'ddl_states']) !!}
        @if ($errors->has('state'))
            <span class="help-block">
                <strong>{{ $errors->first('state') }}</strong>
            </span>
        @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        {!! Form::label('city', 'City') !!}
        {!! Form::select('city', $cities, old('city'), ['class' => 'form-control select2', 'id' => 'ddl_cities']) !!}
        @if ($errors->has('city'))
            <span class="help-block">
                <strong>{{ $errors->first('city') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
            {!! Form::label('profile_picture', 'Profile Picture') !!}
            {!! Form::file('profile_picture') !!}
            @if ($errors->has('profile_picture'))
                <span class="help-block">
                    <strong>{{ $errors->first('profile_picture') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-md-6">
        @if ( isset($record) && is_file('frontend/images/profile/'.$record->profile_picture) )
            {!! '<a href="'.asset('frontend/images/profile/' . $record->profile_picture).'" class="cboxImages">' . Html::image('frontend/images/profile/' . $record->profile_picture, null, ['class' => 'img-responsive pad']) . '</a>' !!}
        @endif
        </div>
    </div>
</div>

<div class="pull-left">
    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat']) !!}
    <a href="{{ backend_url($moduleProperties['controller']) }}" type="button" class="btn btn-default btn-flat">Cancel</a>
</div>

@section('inlineJS')

    <script type="text/javascript">
        var url='{!! URL::to('/') !!}';

        // Fetch cities
        $('#ddl_states').on('change', function() {
            $.getJSON(url + '/backend/cities/' + this.value, function(data) {
                var options = $("#ddl_cities");
                options.html('');
                options.append($('<option></option>').val('').html('Select City'));
                $.each(data, function(key, val) {
                    options.append($('<option></option>').val(key).html(val));
                });

                options.trigger('chosen:updated')
            });
        })
    </script>

@endsection