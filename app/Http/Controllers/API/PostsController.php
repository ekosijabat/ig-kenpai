<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Post;
use App\Models\PostPicture;
use App\Models\PostLikes;
use App\Models\PostComment;
use Illuminate\Support\Str;
use Validator;
use Auth;

use App\Http\Resources\PostsResource;
use App\Http\Resources\PostLikesListResource;
use App\Http\Resources\PostCommentResource;

class PostsController extends BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $posts = Post::with('post_pictures')->where(['status' => 2, 'user_id' => Auth::user()->id])->whereNull('deleted_by')->get();

            return $this->sendResponse(PostsResource::collection($posts));
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'caption'   => 'required',
            'image'     => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        $input['user_id'] = $input['created_by'] = Auth::user()->id;

        try {
            $posts = Post::create($input);

            $saveImage = [];
            if ($posts && !empty($input['image'])) {
                $path = 'post_pic/' . date('Y') . '/' . date('m') . '/' . date('d');
                \File::ensureDirectoryExists(public_path($path));
                $image_64 = $input['image'];
                foreach ($image_64 as $image64) {
                    $extension = explode('/', explode(':', substr($image64['pic'], 0, strpos($image64['pic'], ';')))[1])[1];
                    $replace = substr($image64['pic'], 0, strpos($image64['pic'], ',')+1);
                    $image = str_replace($replace, '', $image64['pic']);
                    $image = str_replace(' ', '+', $image);
                    $imageName = substr(time(), 6, 8) . Str::random(5) . '_' . $image64['name'];

                    \File::put(public_path($path) . '/' . $imageName, base64_decode($image));

                    $saveImage[] = new PostPicture([
                        'post_id' => $posts->id,
                        'picture' => $imageName,
                        'path' => $path,
                        'created_by' => Auth::user()->id
                    ]);
                }
            }

            $images = $posts->post_pictures()->saveMany($saveImage);

            return $this->sendResponse('', trans('messages.post-success'));

        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Show posts base on user id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $posts = Post::with('post_pictures')->where(['status' => 2, 'user_id' => $id])->whereNull('deleted_by')->get();

            if ($posts->isNotEmpty()) {
                return $this->sendResponse(PostsResource::collection($posts));
            } else {
                return $this->sendResponse('', trans('messages.post-empty'));
            }

        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Process like a post by current user login
     *
     * @return \Illuminate\Http\Response
     */
    public function likes(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'post_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        $input['user_id'] = $input['created_by'] = Auth::user()->id;

        try {
            $check = PostLikes::where(['post_id' => $input['post_id'], 'user_id' => Auth::user()->id])->first();

            if (!$check) {
                $likes = PostLikes::create($input);

                if ($likes) {
                    $post = Post::where('id', $input['post_id'])->first();
                    $post->total_likes = $post->total_likes + 1;
                    $post->save();
                }
                return $this->sendResponse('', trans('messages.post-like-success'));
            } else {
                return $this->sendResponse('', trans('messages.post-ever-likes'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Process unlike a post base on current user login
     *
     * @return \Illuminate\Http\Response
     */
    public function unlikes(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'post_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        try {
            $unlikes = PostLikes::where(['post_id' => $input['post_id'], 'user_id' => Auth::user()->id])->delete();

            if ($unlikes) {
                $post = Post::where('id', $input['post_id'])->first();
                $post->total_likes = ($post->total_likes > 0) ? $post->total_likes - 1 : 0;
                $post->save();
            }
            return $this->sendResponse('', trans('messages.post-unlike-success'));
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get list of likes base on current user login
     *
     * @return \Illuminate\Http\Response
     */
    public function listlikes($id) {
        try {
            $posts = Post::with('post_likes.user')->where('id', $id)->first();

            if ($posts->post_likes->isNotEmpty()) {
                return $this->sendResponse(new PostLikesListResource($posts));
            } else {
                return $this->sendResponse('', trans('messages.post-like-empty'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Process to create comment to a post base on current user login
     *
     * @return \Illuminate\Http\Response
     */
    public function comments(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'post_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        $input['user_id'] = $input['created_by'] = Auth::user()->id;

        try {
            $posts = PostComment::create($input);

            if ($posts) {
                return $this->sendResponse('', trans('messages.post-comment-success'));
            } else {
                return $this->sendError(trans('messages.failed'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get list of comments in a post
     *
     * @return \Illuminate\Http\Response
     */
    public function listcomments($id) {
        try {
            $posts = PostComment::with('user')->where('post_id', $id)->tree()->get()->toTree();

            if ($posts->isNotEmpty()) {
                return $this->sendResponse(PostCommentResource::collection($posts));
            } else {
                return $this->sendResponse('', trans('messages.post-comment-empty'));
            }

        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

}
