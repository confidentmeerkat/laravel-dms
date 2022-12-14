<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Dealing;

use App\Http\Requests\Request;
use App\Rules\ClientOwner;
use App\Rules\DomainOwner;
use App\Rules\Interval;
use App\Services\Application\InputData\DealingUpdateRequest;

final class UpdateRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'subtotal' => 'required|integer',
            'discount' => 'required|integer',
            'interval' => 'required|integer',
            'interval_category' => new Interval(),
            'is_auto_update' => 'required|boolean',
            'is_halt' => 'required|boolean',
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function withValidator(
        \Illuminate\Contracts\Validation\Validator $validator
    ): \Illuminate\Contracts\Validation\Validator {
        $validator->sometimes('domain_id', new DomainOwner(), function ($input) {
            $domainDealing = $this->route()->parameter('domainDealing');

            return $domainDealing->isUnclaimed();
        });

        $validator->sometimes('client_id', new ClientOwner(), function ($input) {
            $domainDealing = $this->route()->parameter('domainDealing');

            return $domainDealing->isUnclaimed();
        });

        $validator->sometimes('billing_date', 'required|date_format:Y-m-d|after:yesterday', function ($input) {
            $domainDealing = $this->route()->parameter('domainDealing');

            return $domainDealing->isUnclaimed();
        });

        return $validator;
    }

    /**
     * @return \App\Services\Application\InputData\DealingUpdateRequest
     */
    public function makeInput(): \App\Services\Application\InputData\DealingUpdateRequest
    {
        return new DealingUpdateRequest($this);
    }
}
