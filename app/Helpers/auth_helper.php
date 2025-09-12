<?php

use CodeIgniter\HTTP\IncomingRequest;

/**
 * Add JWT token from cookie to request header
 */
function addTokenToRequestHeader(IncomingRequest $request): void
{
    $cookieName = config('AuthJWT')->cookieName; // 'jwt_token'
    $authorizationHeader = config('AuthJWT')->authenticatorHeader; // 'Authorization'

    $token = $request->getCookie($cookieName) ?? '';

    // add token to request's Authorization header
    $request->setHeader($authorizationHeader, 'Bearer ' . $token);
}
