<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend\Dns;

use App\Http\Requests\Request;
use App\Services\Application\InputData\DnsStoreRequest;

class StoreRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'prefix' => 'nullable|string',
            'domain_id' => 'required|integer',
            'type_id' => 'required|integer',
            'value' => 'nullable|string',
            'ttl' => 'nullable|integer',
            'priority' => 'nullable|integer',
        ];
    }

    /**
     * @return \App\Services\Application\InputData\DnsStoreRequest
     */
    public function makeInput(): \App\Services\Application\InputData\DnsStoreRequest
    {
        return new DnsStoreRequest($this);
    }
}