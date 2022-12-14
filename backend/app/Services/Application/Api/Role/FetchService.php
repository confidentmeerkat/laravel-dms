<?php

declare(strict_types=1);

namespace App\Services\Application\Api\Role;

use App\Constants\RoleConstant;
use App\Http\Resources\RoleResource;

use Illuminate\Support\Facades\Auth;

final class FetchService
{
    private $roles;

    /**
     * @param \App\Infrastructures\Queries\Role\EloquentRoleQueryServiceInterface $eloquentRoleQueryService
     */
    public function __construct(
        \App\Infrastructures\Queries\Role\EloquentRoleQueryServiceInterface $eloquentRoleQueryService
    ) {
        $user = Auth::user();

        $adminRole = $eloquentRoleQueryService->findById(RoleConstant::DEFAULT_ROLE_ID);

        $this->roles = $eloquentRoleQueryService->getByCompanyId($user->company_id);

        $this->roles->prepend($adminRole);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponse(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return RoleResource::collection($this->roles);
    }
}
