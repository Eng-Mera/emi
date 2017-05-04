<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;

use App\Rate;
use App\Reply;
use App\Restaurant;
use App\Review;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Request;

/**
 * Review Reply.
 *
 * @Resource("Review Reply", uri="/api/v1/review/{review_id}/reply")
 */
class ApiReviewReplyController extends Controller
{
    use Helpers;

    public function index(\Illuminate\Http\Request $request , $id)
    {
        $replies = Reply::where('review_id' , $id )->get();

        return $this->response->created('', $replies);
    }

    /**
     * Create Reply
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("title", type="string", required=true, description="The reply title."),
     *      @Parameter("comment", type="string", required=true, description="The reply body."),
     *      @Parameter("review_id", type="string", required=true, description="The review id."),
     * })
     */
    public function store(Requests\ReplyRequest $request, $reviewId)
    {
        $currentUser = User::getCurrentUser();

        $review = Review::findOrFail($reviewId);

        $restaurant = Restaurant::findOrFail($review->restaurant_id);

        if (!$restaurant->isOwner()) {
            return $this->response->errorForbidden();
        }

        //Get Inputs
        $replyData = $request->only(['title', 'comment']);

        $reviewData = [
            'title' => $replyData['title'],
            'comment' => $replyData['comment'],
            'user_id' => $currentUser->id,
            'review_id' => $review->id
        ];

        $reply = Reply::create($reviewData);

        return $this->response->created('', $reply);
    }

    /**
     * Show Reply
     *
     * @Get("/{reply_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("review_id", type="string", required=true, description="The review id."),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("reply_id", type="string", required=true, description="The id of reply."),
     * })
     */
    public function show($reviewId, $id)
    {
        $rate = Reply::findOrFail($id);

        return $this->response->created('', $rate);
    }

    /**
     * Update Reply
     *
     * @Put("/{reply_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("review_id", type="string", required=true, description="The review id."),
     *      @Parameter("reply_id", type="string", required=true, description="The reply id."),
     *      @Parameter("title", type="string", required=true, description="The reply title."),
     *      @Parameter("comment", type="string", required=true, description="The reply body."),
     * })
     */
    public function update(Requests\ReplyRequest $request, $reviewId, $id)
    {
        //Get Inputs
        $replyData = $request->only(['title', 'comment']);

        $reply = Reply::findOrFail($id);

        $reply->fill($replyData)->save();

        $reply = Reply::find($reply->id);

        return $this->response->created('', $reply);
    }

    /**
     * Delete reply
     *
     * @Delete("/{reply_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *     @Parameter("review_id", type="string", required=true, description="The review id."),
     *      @Parameter("reply_id", type="string", required=true, description="The id of reply.")
     * })
     */
    public function destroy($reviewId, $id)
    {
        $reply = Reply::findOrFail($id);

        $reply->delete();

        if (!$reply) {
            return $this->response->errorNotFound(trans('The requested reply is no longer available!'));
        }

        return $this->response->created('', 1);
    }

}
