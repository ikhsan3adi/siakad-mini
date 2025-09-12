<?php

namespace App\Controllers;

use App\Validation\CustomValidationRules;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLoginController;
use CodeIgniter\Shield\Authentication\JWTManager;

class LoginController extends ShieldLoginController
{
    public function loginView()
    {
        // Add JWT from cookie to Authorization header
        // because login route is not protected by the CookieJWTAuthFilter
        addTokenToRequestHeader($this->request);

        if (auth('jwt')->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect());
        }

        return $this->view(setting('Auth.views')['login']);
    }

    public function loginAction(): RedirectResponse
    {
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = $this->getValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        /** @var array $credentials */
        $credentials             = $this->request->getPost(setting('Auth.validFields')) ?? [];
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');

        // Check the credentials with the Session authenticator
        $result = auth('session')->getAuthenticator()->check($credentials);

        //! Credentials mismatch.
        if (!$result->isOK()) {
            return redirect()
                ->route('login')
                ->withInput()
                ->with('error', $result->reason());
        }

        // Credentials match. Generate JWT
        /** @var JWTManager $jwtmanager */
        $jwtmanager = service('jwtmanager');

        $user = $result->extraInfo();

        $jwt = $jwtmanager->generateToken($user);

        // Put JWT in cookie
        $this->response->setCookie(
            name: 'jwt_token',
            value: $jwt,
            expire: HOUR,
            httponly: true,
            samesite: 'strict'
        );

        return redirect()
            ->to(config('Auth')->loginRedirect())
            ->withCookies();
    }

    protected function getValidationRules(): array
    {
        $rules = new CustomValidationRules();

        return $rules->getLoginRules();
    }

    public function logoutAction(): RedirectResponse
    {
        // Delete the JWT cookie
        $this->response->deleteCookie('jwt_token');

        return redirect()
            ->to(config('Auth')->logoutRedirect())
            ->with('message', lang('Auth.successLogout'))
            ->withCookies();
    }
}
