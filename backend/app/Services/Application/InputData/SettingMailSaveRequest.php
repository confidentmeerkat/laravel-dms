<?php

declare(strict_types=1);

namespace App\Services\Application\InputData;

use App\Infrastructures\Models\MailCategory;
use App\Infrastructures\Models\UserMailSetting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

final class SettingMailSaveRequest
{
    private $userMailSettings;

    /**
     * @param \App\Http\Requests\Api\Setting\SaveMailRequest $saveRequest
     */
    public function __construct(
        \App\Http\Requests\Api\Setting\SaveMailRequest $saveRequest
    ) {
        $this->userMailSettings = new Collection();

        $mailSettingParameter = $saveRequest->request->all();

        $mailCategories = MailCategory::get();

        foreach ($mailCategories as $mailCategory) {
            $parameter = array_merge($mailSettingParameter[$mailCategory->name], [
                'user_id' => Auth::id(),
                'mail_category_id' => $mailCategory->id,
            ]);

            if (! $mailCategory->hasDays()) {
                $parameter = array_merge($parameter, [
                    'notice_number_days' => 0,
                ]);
            }

            $userMailSetting = new UserMailSetting($parameter);

            $this->userMailSettings->push($userMailSetting);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInput(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userMailSettings;
    }
}
