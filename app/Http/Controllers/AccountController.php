<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountFormRequest;
use App\Mail\AccountVerificationFailed;
use App\Services\RegonService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateAccountFormRequest $request, RegonService $regonService)
    {
        $data = $request->all();
        $result = $regonService->searchRecord($data);

        if (is_array($result)) {
            $message = __('regon.verification_succeded', Arr::only($result, ['name', 'address']));
        } else {
            $message = __('regon.verification_failed');

            Mail::to(config('support.email'), config('support.name'))
                ->send(new AccountVerificationFailed());
        }
        return redirect()->route('home')->with(compact('message'));
    }
}
