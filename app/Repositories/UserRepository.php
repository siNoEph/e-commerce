<?php
/**
 * Created by PhpStorm.
 * User: bihama
 * Date: 17/02/2016
 * Time: 21.54
 */

namespace App\Repositories;

use App\Events\UserRegistered;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Criteria\RequestCriteria;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository extends Repository
{
    protected $fieldSearchable = ['username', 'email'];

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function registerEmail($email, $role = 'member')
    {
        $credentials = compact('email');

        return $this->register($credentials);
    }

    public function registerAndActivate(array $credentials, array $profile = [], $role = 'member')
    {
        $this->register($credentials, $profile, $role, true);
    }

    public function registerSocial($driver, $appid, array $credentials, array $profile = [], $role = 'member', $activated = false)
    {
        $user = $this->register($credentials, $profile, $role, $activated);

        $user->socialite()->forceFill([
            'name'  => $driver,
            'appid' => $appid
        ]);
    }

    public function register(array $credentials, array $profile = [], $role = 'member', $activated = false)
    {
        if (array_key_exists('password', $credentials)) {
            if (!empty($credentials['email'])) {
                $credentials['password'] = $this->createPassword($credentials['password']);
            } else {
                unset($credentials['password']);
            }
        }

        $user = $this->create($credentials);

        if (!$activated) {
            $this->generateActivationCode($user);
        }

        if (!$user->hasProfile()) {
            $user->profile()->create($profile);
        } else {
            $user->profile()->update($profile);
        }

        $role = Role::where('slug', $role)->first();

        if ($role) {
            $user->roles()->attach($role);
        }

        return $user->load('suppliers');
    }

    public function authenticate(array $credentials)
    {
        $credentials    = collect($credentials);
        $password       = $credentials->pull('password');
        $user           = $this->findWhere($credentials->toArray())->first();

        if ($user) {
            if (app('hash')->check($password, $user->password)) {
                return [
                    'status'    => 'success',
                    'user'      => $user,
                    'token'     => $this->getToken($user),
                ];
            }
        }

        return [
            'status'    => 'failed',
            'message'   => 'Credentials is not valid.'
        ];
    }

    public function authenticateById($id)
    {
        try {
            $user = $this->find($id);

            return [
                'status'    => 'success',
                'user'      => $user,
                'token'     => $this->getToken($user),
            ];
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getToken(User $user)
    {
        return JWTAuth::fromUser($user);
    }

    public function setProfile(User $user, array $profile)
    {
        if ($user->hasProfile()) {
            $user->profile()->update($profile);
        } else {
            $user->profile()->create($profile);
        }

        return $user;
    }

    public function generateActivationCode(User $user)
    {
        $activation_code = str_random(6);

        $user->forceFill(compact('activation_code'));
        $user->save();

        return $user->makeVisible('activation_code');
    }

    public function resetPassword($email, $remember_token, $password)
    {
        $user = $this->findWhere(compact('email', 'remember_token'))->first();

        if ($user) {
            $this->setPassword($user, $password);

            $user->forceFill(['remember_token' => null]);
            $user->save();

            return $user;
        } else {
            return false;
        }
    }

    public function createPassword($plain)
    {
        return $this->makeModel()->createPassword($plain);
    }

    public function setPassword(User $user, $plain)
    {
        $user->forceFill([
            'password' => $this->createPassword($plain)
        ]);
        $user->save();

        return $user;
    }

    public function generateRememberToken(User $user)
    {
        $remember_token = str_random(100);

        $user->forceFill(compact('remember_token'));
        $user->save();

        return $user;
    }
}