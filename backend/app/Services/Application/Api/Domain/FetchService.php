<?php

declare(strict_types=1);

namespace App\Services\Application\Api\Domain;

use App\Http\Resources\DomainResource;
use App\Infrastructures\Models\User;
use Illuminate\Support\Facades\Auth;

final class FetchService
{
    private $domains;

    /**
     * @param \App\Infrastructures\Queries\Domain\EloquentDomainQueryServiceInterface $eloquentDomainQueryService
     */
    public function __construct(
        \App\Infrastructures\Queries\Domain\EloquentDomainQueryServiceInterface $eloquentDomainQueryService
    ) {
        $user = User::find(Auth::id());

        if ($user->isCompany()) {
            $this->domains = $eloquentDomainQueryService->getByUserIds($user->getMemberIds());
        } else {
            $this->domains = $user->domains;
        }
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponse(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DomainResource::collection($this->domains);
    }
}
