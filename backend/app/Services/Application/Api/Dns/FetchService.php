<?php

declare(strict_types=1);

namespace App\Services\Application\Api\Dns;

use App\Http\Resources\DnsResource;
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

        $this->domains = $eloquentDomainQueryService->getByUserIds($user->getMemberIds());
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getResponse(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DnsResource::collection($this->domains);
    }
}
