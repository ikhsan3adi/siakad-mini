<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Shield\Authentication\Authenticators\JWT;
use CodeIgniter\Shield\Filters\JWTAuth as ShieldJWTAuthFilter;

class CookieJWTAuthFilter extends ShieldJWTAuthFilter
{
    public function before(RequestInterface $request, $arguments = null)
    {
        assert($request instanceof IncomingRequest);

        addTokenToRequestHeader($request); // Add JWT from cookie to Authorization header

        /** @var JWT $authenticator */
        $authenticator = auth('jwt')->getAuthenticator();

        $token = $authenticator->getTokenFromRequest($request);

        $result = $authenticator->attempt(['token' => $token]);

        //! Token invalid.
        if (!$result->isOK()) {
            return redirect()
                ->route('login')
                ->with('error', $result->reason());
        }

        // Token valid.
        if (setting('Auth.recordActiveDate')) {
            $authenticator->recordActiveDate();
        }
    }
}
