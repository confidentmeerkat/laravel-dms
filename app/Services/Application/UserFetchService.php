<?php

declare(strict_types=1);

namespace App\Services\Application;

use App\Http\Resources\UserResource;
use App\Infrastructures\Models\User;

use Illuminate\Support\Facades\Auth;

final class UserFetchService
{
    private $users;

    /**
     * @param \App\Infrastructures\Queries\User\EloquentUserQueryServiceInterface $eloquentUserQueryService
     */
    public function __construct(
        \App\Infrastructures\Queries\User\EloquentUserQueryServiceInterface $eloquentUserQueryService
    ) {
        $user = User::find(Auth::id());

        if (! $user->isCompany()) {
            abort(403);
        }

        $this->users = $eloquentUserQueryService->getActiveUsersByCompanyId($user->company_id);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponseData(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection($this->users);
    }
}
