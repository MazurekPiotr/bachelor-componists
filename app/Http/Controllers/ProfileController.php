<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Displays a user's profile.
     * This is publicly available.
     *
     * @param  Illuminate\Http\Request $request
     * @param  App\User                $user
     * @return Illuminate\Http\Response
     */
    public function index (Request $request, User $user)
    {
        return view('user.profile.index', [
            'user' => $user,
        ]);
    }

    public function privacyPolicy()
    {
      return view('privacy-policy');
    }

    public function termsOfUse()
    {
      return view('terms-of-user');
    }
}
