<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOpinionRequest;
use App\Http\Requests\UpdateOpinionRequest;
use App\Repositories\OpinionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Response;
use Flash;

class OpinionController extends AppBaseController
{
    /** @var  OpinionRepository */
    private $opinionRepository;

    public function __construct(OpinionRepository $opinionRepo)
    {
        $this->opinionRepository = $opinionRepo;
    }

    /**
     * Display a listing of the Opinion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $opinions = $this->opinionRepository->allQuery(['user_id' => $user_id])->paginate(10);

        return view('opinions.index')
            ->with('opinions', $opinions);
    }

    /**
     * Store a newly created Opinion in storage.
     *
     * @param CreateOpinionRequest $request
     *
     * @return Response
     */
    public function store(CreateOpinionRequest $request)
    {
        $input = [
            'doc_delivery' => 0,
            'payment' => 0,
            'cooperation' => 0,
        ];
        $input = array_merge($input, $request->all());
        $user = Auth::user();

        if ($user->opinions->firstWhere('nip', Arr::get($input, 'nip'))) {
            return redirect()->route('home')->with(['error' => __('regon.no_more_opinion')]);
        }
        $input['user_id'] = $user->id;

        $this->opinionRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/opinions.singular')]));

        return redirect(route('opinions.index'));
    }

    /**
     * Remove the specified Opinion from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $opinion = $this->opinionRepository->find($id);

        if (empty($opinion)) {
            Flash::error(__('messages.not_found', ['model' => __('models/opinions.singular')]));

            return redirect(route('opinions.index'));
        }

        $this->opinionRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/opinions.singular')]));

        return redirect(route('opinions.index'));
    }
}
