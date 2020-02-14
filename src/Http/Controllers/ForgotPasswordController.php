<?php

namespace Bitfumes\Multiauth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('multiauth::admin.passwords.email');
    }

    /**
     * @return mixed
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * @param Request $request
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    /**
    * Get the response for a successful password reset link.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $response
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return response('Email Sent Successfully', Response::HTTP_ACCEPTED);
        }
        return back()->with('status', trans($response));
    }
}
