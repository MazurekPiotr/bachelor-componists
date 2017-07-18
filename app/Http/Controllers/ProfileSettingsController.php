<?php

namespace App\Http\Controllers;

use Hash;
use Session;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileSettingsFormRequest;
use File;
use Image;
use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monarobase\CountryList\CountryList;

class ProfileSettingsController extends Controller
{

    public function index (Request $request, User $user)
    {
        // if it's not the current user's profile, let's abort!
        if ($user->id !== \Auth::user()->id) {
            abort(403);
        }
        $countryList = new CountryList();
        $countries = $countryList->getList('en', 'php');

        return view('user.profile.settings.index', [
            'user' => $user,
            'countries' => $countries,
            'password_update_success' => Session::get('password_update_success'),
        ]);
    }

    /**
     * @param ProfileSettingsFormRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update (ProfileSettingsFormRequest $request, User $user)
    {
        // if it's not the current user's profile, let's abort!
        if ($user->id !== \Auth::user()->id) {
            abort(403);
        }

        // don't need to authorize this action, as only making changes to logged in user.
        if ($request->hasFile('avatar')) {
            // wants to update avatar
            $fileId = $request->hasFile('avatar');
            $request->file('avatar')->move(storage_path() . '/avatars', $fileId);

            $path = storage_path() . '/avatars/' . $fileId ;
            $fileName = $fileId . '.png';

            $img = Image::make($path)->encode('png')->fit(300, 300, function ($constraint) {
                $constraint->upsize();
            });

            $uploadImg = $img->stream()->detach();

            Storage::disk('s3')->put('/avatars/'. $user->id .'/avatar.jpg', $uploadImg, 'public');

            $user->avatar = $fileName;
            $user->imageURL = 'https://tracks-bachelor.s3.eu-west-2.amazonaws.com/avatars/'. $user->id .'/avatar.jpg';
            $user->save();


            // not necessarily uploaded to s3 yet.. but its ok for now
            Session::flash('avatar_image_uploaded', true);
        }

        // used in combination with old('oldPassword'), to check if the password has actually been sunbmitted.
        // not the best design, but logic is fine for now..
        $password_update_success = false;

        if($request->has('country')) {
            $user = $request->user();
            $user->country = $request->country;

            $user->save();
        }

        if ($request->has('oldPassword')) {
            // wants to change password
            // only need to check one password input field, as validation will take care of checking that text was entered into the newPassword field

            $user = $request->user();
            $user->password = Hash::make($request->newPassword);
            $user->save();

            // validation is taking care of critera and checking against current password, to make sure user is a valid user
            $password_update_success = true;
        }

        Session::flash('password_update_success', $password_update_success);

        return redirect()->route('user.profile.settings.index', [
            'user' => $user,
        ]);
    }
}
