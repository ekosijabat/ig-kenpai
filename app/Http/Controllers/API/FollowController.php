<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use Auth;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\FollowerResource;
use App\Models\Follower;

class FollowController extends BaseController {
    /**
     * Display a listing of the followers.
     *
     * @return \Illuminate\Http\Response
     */
    public function followers($id='') {
        $id = (!empty($id)) ? $id : Auth::user()->id;

        try {
            $followers = Follower::with('followers')
                        ->where(['user_id' => $id, 'status' => 1])
                        ->get();

            if ($followers->isNotEmpty()) {
                return $this->sendResponse(FollowerResource::collection($followers));
            } else {
                return $this->sendResponse('', trans('messages.zero-followers'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display a listing of the following.
     *
     * @return \Illuminate\Http\Response
     */
    public function following($id='') {
        $id = (!empty($id)) ? $id : Auth::user()->id;

        try {
            $following = Follower::with('following')
                    ->select([
                        '*',
                        \DB::raw("'following' action")
                    ])
                    ->where(['follower_id' => $id, 'status' => 1])
                    ->get();

            if ($following->isNotEmpty()) {
                return $this->sendResponse(FollowerResource::collection($following));
            } else {
                return $this->sendResponse('', trans('messages.zero-following'));
            }
        } catch (\Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }

}
