<?php

declare(strict_types=1);

namespace App\Rules;

use App\Infrastructures\Models\Domain;

use App\Infrastructures\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

final class DomainNameDuplicate implements Rule
{
    private $domain;

    /**
     * @param \App\Infrastructures\Models\Domain $domain
     */
    public function __construct(\App\Infrastructures\Models\Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = User::find(Auth::id());

        if ($user->isCompany()) {
            $domain = Domain::where('name', $value)->whereIn('user_id', $user->getMemberIds())
            ->first();
        } else {
            $domain = Domain::where('name', $value)->where('user_id', $user->id)
            ->first();
        }

        if (isset($domain) && $domain->id !== $this->domain->id) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'Domain already exists';
    }
}
