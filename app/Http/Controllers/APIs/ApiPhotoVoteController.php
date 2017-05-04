<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\Vote;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Photo Votes.
 *
 * @Resource("Photo Votes", uri="/api/v1/file/{file_id}/vote")
 */
class ApiPhotoVoteController extends Controller
{

    use Helpers;

    /**
     * List File Votes
     *
     * @Get("{?per_page,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=false, description="The photo id.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index($fileId)
    {
        $perPage = Request::get('per_page', 10);
        $count = Request::get('vote_count', false);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $file = File::whereId($fileId)->firstOrFail();

        $with = ['file', 'file.meta'];

        if (!$count) {
            $votes = Vote::with($with)->where('file_id', $file->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $votesUp = Vote::where(['file_id' => $file->id, 'vote_up' => 1])->count();
            $votesDown = Vote::where(['file_id' => $file->id, 'vote_up' => 0])->count();

            $votes = ['votes_up' => $votesUp, 'vote_down' => $votesDown];
        }

        return $this->response->created('', $votes);
    }

    /**
     * Create Vote
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("vote_up", type="boolean", required=true, description="1 for true and 0 for false."),
     *      @Parameter("vote_down", type="boolean", required=true, description="1 for true and 0 for false."),
     * })
     */
    public function store(Requests\VoteRequest $request, $fileId)
    {
        //Get Inputs
        $inputs = $request->only(['vote_up', 'vote_down']);

        $file = File::whereId($fileId)->firstOrFail();

        $inputs['file_id'] = $file->id;
        $inputs['user_id'] = User::getCurrentUser()->id;

        if ($inputs['vote_up'] == $inputs['vote_down']) {
            return $this->response->errorBadRequest(trans('Vote up and Vote down must be different.'));
        }

        if (Vote::where($inputs)->count()) {
            return $this->response->errorBadRequest(trans('You already Voted for this picture'));
        }

        if ($vote = Vote::where(['file_id' => $file->id, 'user_id' => User::getCurrentUser()->id])->first()) {

            $vote->vote_up = $inputs['vote_up'];
            $vote->vote_down = $inputs['vote_down'];
            $vote->save();
        } else {
            //Create Vote
            $vote = Vote::create($inputs);
        }

        $vote = Vote::find($vote->id);

        return $this->response->created('', $vote);
    }

    /**
     * Show Vote
     *
     * @Get("/{vote_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("vote_id", type="integer", required=true, description="The id of vote."),
     * })
     */
    public function show($fileId, $id)
    {
        $vote = Vote::whereId($id)->firstOrFail();
        return $this->response->created('', $vote);
    }

    /**
     * Update Vote
     *
     * @Put("/{vote_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("vote_id", type="integer", required=true, description="The id of vite."),
     *      @Parameter("vote_up", type="boolean", required=true, description="1 for true and 0 for false."),
     *      @Parameter("vote_down", type="boolean", required=true, description="1 for true and 0 for false."),
     * })
     */
    public function update(Requests\VoteRequest $request, $fileId, $id)
    {
        $vote = Vote::whereId($id)->firstOrFail();
        //Get Inputs
        $inputs = $request->only(['vote_up', 'vote_down']);

        $file = File::whereId($fileId)->firstOrFail();

        if ($inputs['vote_up'] == $inputs['vote_down']) {
            return $this->response->errorBadRequest(trans('Vote up and Vote down must be different.'));
        }

        if (Vote::where($inputs)->count()) {
            return $this->response->errorBadRequest(trans('You already Voted for this picture'));
        }

        if ($vote = Vote::where(['file_id' => $file->id, 'user_id' => User::getCurrentUser()->id])->first()) {
            //Update Restaurant
            $vote->fill($inputs)->save();
        }

        $vote = Vote::find($vote->id);

        return $this->response->created('', $vote);
    }

    /**
     * Delete Vote
     *
     * @Delete("/{vote_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("vote_id", type="integer", required=true, description="The id of vote."),
     * })
     */
    public function destroy($fileId, $id)
    {
        $file = File::whereId($fileId)->firstOrFail();

        $vote = Vote::where(['id' => $id, 'file_id' => $file->id])->delete();

        if (!$vote) {
            return $this->response->errorNotFound(trans('Vote not found!'));
        }

        return $this->response->created('', $vote);
    }

}
