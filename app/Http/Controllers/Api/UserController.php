<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserAuthenticationRequest;
use App\Http\Resources\User\UserCollection;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $apiResponse, $userRepository;
    public function __construct(ApiResponse $apiResponse, UserRepositoryInterface $userRepository)
    {
        $this->apiResponse = $apiResponse;
        $this->userRepository = $userRepository;
    }

    public function getUserInfo(Request $request){
        if(!$request->has("id")){
            return $this->apiResponse->setError(__("user id is required"))->setData()->getJsonResponse();
        }
        $user = $this->userRepository->find($request->id);
        if(!$user){
            return $this->apiResponse->setError(__("user not found"))->setData()->getJsonResponse(404);
        }
        return $this->apiResponse->setSuccess(__("User data retreived successfully"))->setData(new UserResource($user))->getJsonResponse();
    }

    public function infoUpdate(UserAuthenticationRequest $userRequest){
        try {

            DB::beginTransaction();
            $user = Auth::guard('api')->user();
            if (empty($user)) {
                return $this->apiResponse->setError(__("expired_token"))->setData()->getJsonResponse();
            }

            $updated_user = $this->userRepository->update($userRequest->id,$userRequest->validated());
            DB::commit();

            return $this->apiResponse
                ->setSuccess("User updated successfully")
                ->setData(new UserResource($updated_user))
                ->getJsonResponse();

        } catch(Exception $exception){
            DB::rollback();
            return $this->apiResponse->setError($exception->getMessage())->setData()->getJsonResponse();
        }
    }

    public function delete(UserAuthenticationRequest $userRequest){
        $user = $this->userRepository->delete($userRequest->id);
        return $this->apiResponse
                ->setSuccess("User deleted successfully")
                ->setData(new UserResource($user))
                ->getJsonResponse();
    }
    public function allUsers(){
        $users = $this->userRepository->all();
        return $this->apiResponse
                ->setSuccess("Users retrieved successfully")
                ->setData(new UserCollection($users))
                ->getJsonResponse();
    }
}
