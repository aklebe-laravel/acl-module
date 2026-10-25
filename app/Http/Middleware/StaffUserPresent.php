<?php

namespace Modules\Acl\app\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Acl\app\Models\AclResource;
use Modules\Acl\app\Services\UserService;

class StaffUserPresent
{
    /**
     * Handle an incoming request.
     *
     * @param  Request                                        $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        if ($userService->currentUser()->hasAclResource(AclResource::RES_STAFF)) {
            return $next($request);
        }

        // access not allowed
        return response(view('content-pages.access-denied'));
    }
}
