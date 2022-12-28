<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\UserResource;

use App\Models\User;
use App\Models\Follower;

class UserController extends BaseController
{
    /**
     * Get profile current login user
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $user = User::findOrFail(Auth::user()->id);

            if (!empty($user)) {
                return $this->sendResponse(new UserResource($user));
            } else {
                return $this->sendResponse('', trans('message.failed'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get profile base on user id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $user = User::findOrFail($id);

            if (!empty($user)) {
                return $this->sendResponse(new UserResource($user));
            } else {
                return $this->sendResponse('', trans('message.failed'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the followers.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        try {
            $search = User::where('name','LIKE',"%{$request->search}%")
                    ->orWhere('username','LIKE',"%{$request->search}%")
                    ->orWhere('first_name','LIKE',"%{$request->search}%")
                    ->orWhere('last_name','LIKE',"%{$request->search}%")
                    ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%".$request->search."%")
                    ->get();

            if ($search->isNotEmpty()) {
                return $this->sendResponse(UserResource::collection($search));
            } else {
                return $this->sendResponse('', trans('messages.not-registered-yet'));
            }

        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Process to follow other user
     *
     * @return \Illuminate\Http\Response
     */
    public function follows(Request $request) {
        $input = $request->all();

        try {
            $check = Follower::where(['user_id' => $request->user_id, 'follower_id' => Auth::user()->id])->first();

            if (!$check) {
                $input['follower_id'] = $input['created_by'] = Auth::user()->id;

                $follow = Follower::create($input);

                if ($follow) {
                    return $this->sendResponse('', trans('messages.follow-success'));
                } else {
                    return $this->sendError(trans('messages.failed'));
                }
            } else {
                return $this->sendResponse('', trans('messages.follow-already'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Process to unfollow other user
     *
     * @return \Illuminate\Http\Response
     */
    public function unfollow(Request $request) {
        $input = $request->all();

        try {
            $check = Follower::where(['user_id' => $request->user_id, 'follower_id' => Auth::user()->id])->first();

            if (!$check) {
                return $this->sendError(trans('messages.follow-first'));
            } else {
                $unfollow = Follower::where(['user_id' => $request->user_id, 'follower_id' => Auth::user()->id])->delete();

                if ($unfollow) {
                    return $this->sendResponse('', trans('messages.unfollow'));
                } else {
                    return $this->sendError(trans('messages.failed'));
                }
            }

        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

}
