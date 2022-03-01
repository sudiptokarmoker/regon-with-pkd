<?php

namespace App\Rules;

use App\Mail\AccountVerificationFailed;
use App\Services\RegonService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class CheckNipPkdRule implements Rule
{
    /**
     * @var RegonService
     */
    protected $regonService;
    protected const KEY_PKD = "6920Z";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->regonService = app()->make(RegonService::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $record = $this->regonService->searchRecord(['nip' => $value]);
        if (is_array($record)) {
            $pkds = $this->regonService->getCompanyPKDList(
                Arr::get($record, 'regon'),
                Arr::get($record, 'silosID')
            );

            if (is_array($pkds) AND in_array(self::KEY_PKD, $pkds)) {
                session([
                    'registration.nazwa' => $record['name'],
                    'registration.address' => $record['address']
                ]);
                return true;
            }
        }

        $this->sendMailToSupport();
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.nip.has_right_pkd');
    }

    public function sendMailToSupport()
    {
        $name  = request()->get('name');
        $email = request()->get('email');
        $nip   = request()->get('nip');

        Mail::to(config('support.email'), config('support.name'))
            ->send(new AccountVerificationFailed(compact('name', 'email', 'nip')));
    }
}
