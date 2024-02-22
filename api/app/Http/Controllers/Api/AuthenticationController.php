<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Auth endpoints
 */
class AuthenticationController extends Controller
{
    /**
     * Shows authenticated user information
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function user()
    {
        return auth()->user();
    }
}
