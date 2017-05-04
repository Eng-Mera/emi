<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\PhotoComment;
use App\Comment;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Photo Comments.
 *
 * @Resource("Photo Comments", uri="/api/v1/file/{file_id}/comment")
 */
class ApiPhotoCommentController extends Controller
{

    use Helpers;

    /**
     * List Photo comments
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

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $file = File::whereId($fileId)->firstOrFail();

        $with = ['file', 'file.meta', 'user' => function ($query) {
            return $query->select('id','username','name');
        }, 'user.profilePicture' =>function($query){
            return $query->select('id','user_id', 'imageable_id', 'imageable_type', 'filename');
        }];

        $comments = PhotoComment::with($with)->where('file_id', $file->id)->orderBy($orderBy, $orderDir)->paginate($perPage);

        return $this->response->created('', $comments);
    }

    /**
     * Create Comment
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("comment", type="string", required=true, description="The comment body."),
     * })
     */
    public function store(Requests\PhotoCommentRequest $request, $fileId)
    {
        //Get Inputs
        $inputs = $request->only(['comment']);

        $file = File::whereId($fileId)->firstOrFail();

        $inputs['file_id'] = $file->id;
        $inputs['user_id'] = User::getCurrentUser()->id;

        //Create Comment
        $comment = PhotoComment::create($inputs);

        $comment = PhotoComment::find($comment->id);

        return $this->response->created('', $comment);
    }

    /**
     * Show Comment
     *
     * @Get("/{comment_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("comment_id", type="integer", required=true, description="The id of comment."),
     * })
     */
    public function show($fileId, $id)
    {
        $comment = PhotoComment::whereId($id)->firstOrFail();
        return $this->response->created('', $comment);
    }

    /**
     * Update Comment
     *
     * @Put("/{comment_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("comment_id", type="integer", required=true, description="The id of comment."),
     *      @Parameter("comment", type="string", required=true, description="The comment body."),
     * })
     */
    public function update(Requests\PhotoCommentRequest $request, $fileId, $id)
    {
        $comment = PhotoComment::whereId($id)->firstOrFail();
        //Get Inputs
        $inputs = $request->only(['comment']);

        $file = File::whereId($fileId)->firstOrFail();

        $comment->fill($inputs)->save();

        $comment = PhotoComment::find($comment->id);

        return $this->response->created('', $comment);
    }

    /**
     * Delete Comment
     *
     * @Delete("/{comment_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("file_id", type="integer", required=true, description="The photo id."),
     *      @Parameter("comment_id", type="integer", required=true, description="The id of comment."),
     * })
     */
    public function destroy($fileId, $id)
    {
        $file = File::whereId($fileId)->firstOrFail();

        $comment = PhotoComment::where(['id' => $id, 'file_id' => $file->id])->delete();

        if (!$comment) {
            return $this->response->errorNotFound(trans('Comment not found!'));
        }

        return $this->response->created('', $comment);
    }

}
