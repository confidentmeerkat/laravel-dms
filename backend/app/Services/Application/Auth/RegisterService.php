<?php

declare(strict_types=1);

namespace App\Services\Application\Auth;

use App\Constants\CompanyConstant;
use App\Constants\RoleConstant;

use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class RegisterService
{
    private $userRepository;

    private $userLatestCodeRepository;

    private $emailVerificationService;

    /**
     * @param \App\Infrastructures\Repositories\User\UserRepositoryInterface $userRepository
     * @param \App\Infrastructures\Repositories\User\UserLatestCodeRepositoryInterface $userLatestCodeRepository
     * @param \App\Infrastructures\Mails\Services\EmailVerificationService $emailVerificationService
     */
    public function __construct(
        \App\Infrastructures\Repositories\User\UserRepositoryInterface $userRepository,
        \App\Infrastructures\Repositories\User\UserLatestCodeRepositoryInterface $userLatestCodeRepository,
        \App\Infrastructures\Mails\Services\EmailVerificationService $emailVerificationService
    ) {
        $this->userRepository = $userRepository;
        $this->userLatestCodeRepository = $userLatestCodeRepository;
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * @param \App\Services\Application\InputData\Auth\RegisterRequest $registerRequest
     * @return void
     */
    public function handle(
        \App\Services\Application\InputData\Auth\RegisterRequest $registerRequest
    ): void {
        $userRequest = $registerRequest->getInput();

        DB::beginTransaction();
        try {
            $code = $this->userLatestCodeRepository->next();

            $user = $this->userRepository->store([
                'name' => $userRequest->name,
                'company_id' => CompanyConstant::INDEPENDENT_COMPANY_ID,
                'role_id' => RoleConstant::DEFAULT_ROLE_ID,
                'code' => $code,
                'email' => $userRequest->email,
                'emoji' => $userRequest->emoji,
                'password' => $userRequest->password,
                'email_verify_token' => $userRequest->email_verify_token,
            ]);

            $this->emailVerificationService->execute($user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception('Failed registration');
        }

        Auth::login($user);
    }
}
