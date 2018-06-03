@extends('layouts.app')

@section('content')
<div id="settings">
    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card">
                    <div class="card-header"><h1 class="center-align">Edit you profile</h1></div>
                    <div class="card-content">
                        @if (Session::get('avatar_image_uploaded') != null)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times<i class="fa fa-close"></i></span></button>
                            <strong>Awesome!</strong> Your avatar will be visible in the next few seconds, depending on how large the file is. Give the page a refresh.
                        </div>
                        @endif
                        @if (Session::get('password_update_success') === true)
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                            <strong>Yipee!</strong> Your password has been updated!
                        </div>
                        @endif
                        <div>
                            <form action="{{ route('user.profile.settings.update', Auth::user()->name) }}" method="post" enctype="multipart/form-data">
                                <div class="image">
                                    @if( Storage::disk('s3')->exists('avatars/'. Auth::user()->id . '/avatar.jpg')  )
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. Auth::user()->id . '/') . 'avatar.jpg' }}" alt="{{ $user->name }}-avatar">
                                    @else
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. 'no-avatar.png') }}" class="img-thumbnail" alt="blank-avatar">
                                    @endif
                                    <div class="file-field input-field">
                                        <div class="btn">
                                            <span>Change</span>
                                            <input type="file" name="avatar">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text">
                                        </div>
                                        @if ($errors->has('avatar'))
                                        <div class="help-block danger">
                                            {{ $errors->first('avatar') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="profile-info col s12">
                                    <div class="row">
                                        <div class="input-field">
                                            <select type="text" name="country">
                                                @foreach ($countries as $key => $country)
                                                @if(Auth::user()->country() == $key)
                                                <option value="{{$key}}" selected>{{ $country }}</option>
                                                @else
                                                <option value="{{$key}}" >{{ $country }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <label>Country</label>
                                            @if ($errors->has('country'))
                                            <div class="help-block danger">
                                                {{ $errors->first('country') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field">
                                            <label>Old Password</label>
                                            <input type="password" name="oldPassword">
                                            @if ($errors->has('oldPassword') && !$password_update_success)
                                            <div class="help-block danger">
                                                {{ $errors->first('oldPassword') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-field">
                                            <label>New Password</label>
                                            <input type="password" name="newPassword">
                                            @if ($errors->has('newPassword'))
                                            <div class="help-block danger">
                                                {{ $errors->first('newPassword') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col s12 centered">
                                    <button type="submit" class="btn btn-default">Update</button>
                                </div>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
