@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>

                <div class="panel-body">
                    @if (Session::get('avatar_image_uploaded') != null)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Awesome!</strong> Your avatar will be visible in the next few seconds, depending on how large the file is. Give the page a refresh.
                        </div>
                    @endif
                    @if (Session::get('password_update_success') === true)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Yipee!</strong> Your password has been updated!
                        </div>
                    @endif
                    <form action="{{ route('user.profile.settings.update', Auth::user()->name) }}" method="post" enctype="multipart/form-data">
                        <h4>Change avatar</h4>
                        @if( Storage::disk('s3')->exists('avatars/'. Auth::user()->id . '/avatar.jpg')  )
                            <img src="{{ Storage::disk('s3')->url('avatars/'. Auth::user()->id . '/') . 'avatar.jpg' }}" class="img-thumbnail" width="100" height="100" alt="{{ $user->name }}-avatar">
                        @else
                            <img src="{{ Storage::disk('s3')->url('avatars/'. 'no-avatar.png') }}" class="img-thumbnail" width="100" height="100" alt="blank-avatar">
                        @endif
                        <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <label for="avatar" class="control-label">Avatar Image</label>
                            <input type="file" name="avatar" id="avatar" class="form-control">
                            @if ($errors->has('avatar'))
                                <div class="help-block danger">
                                    {{ $errors->first('avatar') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label for="country" class="control-label">Avatar Image</label>
                            <select type="text" name="country" id="country" class="form-control">
                                @foreach ($countries as $country)
                                    @if(Auth::user()->country() == $country)
                                        <option value="{{$country}}" selected>{{ $country }}</option>
                                    @else
                                        <option value="{{$country}}" >{{ $country }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                <div class="help-block danger">
                                    {{ $errors->first('country') }}
                                </div>
                            @endif
                        </div>

                        <br />

                        <h4>Change password</h4>
                        <div class="form-group{{ $errors->has('oldPassword') && !$password_update_success ? ' has-error' : '' }}">
                            <label for="oldPassword" class="control-label">Old Password</label>
                            <input type="password" name="oldPassword" id="oldPassword" class="form-control">
                            @if ($errors->has('oldPassword') && !$password_update_success)
                                <div class="help-block danger">
                                    {{ $errors->first('oldPassword') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('newPassword') ? ' has-error' : '' }}">
                            <label for="newPassword" class="control-label">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control">
                            @if ($errors->has('newPassword'))
                                <div class="help-block danger">
                                    {{ $errors->first('newPassword') }}
                                </div>
                            @endif
                            <div class="help-block">
                                Only fill in the password fields if you wish to change your password.
                            </div>
                        </div>

                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default pull-right">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
