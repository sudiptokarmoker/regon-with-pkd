<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckNipFormRequest;
use App\Services\RegonService;
use Illuminate\Support\Arr;

class CheckNipController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CheckNipFormRequest $request, RegonService $regonService)
    {
        $data = $request->all();
        $res = ['nip' => Arr::get($data, 'nip')];
        $result = $regonService->searchRecord($data);
        $resource = is_array($result) ? $result : null;

        if (Arr::get($data, 'check_company')) {
            if ($resource) {
                $res += ['message' => __('regon.nip_entity_found')];
            }
            else {
                $res += ['message' => __('regon.nip_entity_not_found')];
            }
        }

        if (Arr::get($data, 'create_opinion')) {
            if ($resource) {
                $res += ['company' => $resource];
            }
            else {
                $res += ['message' => __('regon.nip_entity_not_found')];
            }
        }
        return redirect()->route('home')->with($res);
    }
}
