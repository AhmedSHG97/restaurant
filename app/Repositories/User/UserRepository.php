<?php

namespace App\Repositories\User;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\Base\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public $model;
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUserByEmail(string $email)
    {
         return $this->model->email($email)->first();
    }
    public function getUserByUsername(string $username){
        return $this->model->username($username)->first();
    }
    public function findUnexpiredUser($id)
    {
        return $this->model->where("id", $id)->where("created_at", ">", Carbon::now()->subHours(1))->first();
    }


    public function getUserByToken($token)
    {
        $rememberToken = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>',  Carbon::now()->subHours(1))
            ->first();
        if (empty($rememberToken)) {
            return null;
        }
        return $this->model->where("email", $rememberToken->email)->first();
    }

    public function allWithoutAuthed(){
        return $this->model->where('id',"!=",Auth::user()->id)->get();
    }
}
