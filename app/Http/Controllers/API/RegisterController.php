<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class RegisterController extends BaseController {
    /**
     * Register API
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required',
            'c_password' => 'required|same:password',
            'username'  => 'required|unique:users,username',
            'phone_number' => 'required',
            'date_of_birth' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        try {
            if (!empty($input['profile_pic'] && !empty($input['profile_pic_name']))) {
                $path = 'profile_pic/' . date('Y') . '/' . date('m') . '/' . date('d');
                $image_64 = $input['profile_pic'];
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                $replace = substr($image_64, 0, strpos($image_64, ',')+1);
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = substr(time(), 6, 8) . Str::random(5) . '_' . $input['profile_pic_name'];

                $input['profile_pic'] = $imageName;
                $input['path'] = $path;
            }

            $user = User::create($input);

            if ($user && !empty($input['profile_pic']) && !empty($input['profile_pic_name'])) {
                \File::put(public_path($path) . '/' . $imageName, base64_decode($image));
            }

            $result['token'] = $user->createToken('kenpai')->accessToken;
            $result['name'] = $user->name;

            return $this->sendResponse($result, trans('messages.account-register-success'));
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Login API
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('messages.validation-failed'), $validator->errors());
        }

        try {
            $user = User::where('email', $request->username)->orWhere('username', $request->username)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $success['token'] = $user->createToken('kenpai')->accessToken;
                    $success['name'] = $user->name;

                    return $this->sendResponse($success, trans('messages.login-success'));
                } else {
                    return $this->sendError('', trans('messages.credential-failed'));
                }
            } else {
                return $this->sendError('', trans('messages.credential-failed'));
            }
        } catch (Exception $e) {
            return $this->sendError('', ['error' => $e->getMessage()]);
        }
    }
}
