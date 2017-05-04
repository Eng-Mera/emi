<?php

namespace App;

use App\Http\Requests\Request;
use Dingo\Api\Facade\API;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\ResourceServer;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use App\Http\Helpers\SearchableTrait;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

    use EntrustUserTrait, SearchableTrait;

    protected $appends = ['user_level'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dob', 'username', 'gender', 'pending', 'created_by', 'intgm_id', 'fb_id', 'google_id', 'about_me'
    ];

    public function getUserLevelAttribute()
    {

        $level = self::calculateUserRate($this->id, false, true);

        return $level;
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.username' => 10,
            'users.email' => 5,
            'roles.name' => 2,
            'profiles.mobile' => 2,
        ],
        'joins' => [
            'profiles' => ['users.id', 'profiles.user_id'],
            'role_user' => ['users.id', 'role_user.user_id'],
            'roles' => ['roles.id', 'role_user.role_id'],
        ],
        'groupBy' => ['users.id']

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot' , 'manage_restaurant_id'
    ];

    public function verifyPassword($username = '', $password = '')
    {
        $credentials = [
            'password' => $password,
        ];

        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

        if (!$isEmail) {

            $credentials['username'] = $username;
        } else {
            $credentials['email'] = $username;
        }

        if (\Auth::once($credentials)) {
            return \Auth::user()->id;
        } else {
            return false;
        }
    }

    public function verifyCode($email = '' , $code = '')
    {
        if (!is_numeric($code)) {
            return false;
        }

        $user = User::where(['special_code' => $code])->first();

        return $user ? $user->id : false;



    }

    /**
     * Validate Current User has role of Restaurant Manager / Restaurant Admin Or Job Seeker.
     *
     * @param string $username
     * @param string $password
     * @return bool|mixed
     */
    public function verifyPasswordAndRole($username = '', $password = '')
    {
        $userId = $this->verifyPassword($username, $password);

        if ($userId && User::find($userId)->hasRole([Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN, Role::JOB_SEEKER])) {
            return $userId;
        } else {
            return false;
        }
    }

    public function verifySocialMedia($socialId, $socialType)
    {

        $socialMedia = ['fb_id' => 'facebook', 'google_id' => 'google', 'intgm_id' => 'instagram'];

        $columnName = array_search($socialType, $socialMedia);

        if (!is_numeric($socialId)) {
            return false;
        }

        $user = User::where([$columnName => $socialId])->first();

        return $user ? $user->id : false;
    }

    /**
     * Validate Current User has role of Restaurant Manager / Restaurant Admin Or Job Seeker.
     *
     * @param $socialId
     * @param $socialType
     * @return bool
     */
    public function verifySocialMediaWithRole($socialId, $socialType)
    {
        $userId = $this->verifySocialMedia($socialId, $socialType);

        if ($userId && User::find($userId)->hasRole([Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN, Role::JOB_SEEKER])) {
            return $userId;
        } else {
            return false;
        }
    }

    public function updateSocialId($socialId, $socialType, $email)
    {
        $socialMedia = ['fb_id' => 'facebook', 'google_id' => 'google', 'intgm_id' => 'instagram'];

        $user = User::whereEmail($email)->first();

        if (!is_numeric($socialId)) {
            return false;
        }

        $user->fill([array_search($socialType, $socialMedia) => $socialId])->save();

        return $user ? $user->id : false;
    }

    /**
     * Validate Current User has role of Restaurant Manager / Restaurant Admin Or Job Seeker.
     *
     * @param $socialId
     * @param $socialType
     * @param $email
     * @return bool
     */
    public function updateSocialIdWithRole($socialId, $socialType, $email)
    {
        $userId = $this->updateSocialId($socialId, $socialType, $email);

        if ($userId && User::find($userId)->hasRole([Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN, Role::JOB_SEEKER])) {
            return $userId;
        } else {
            return false;
        }
    }

    public function profile()
    {
        return $this->hasOne('\App\Profile');
    }

    public function claim()
    {
        return $this->hasOne('\App\Claim');
    }

    public function restaurant()
    {
        return $this->hasMany('\App\Restaurant', 'owner_id');
    }

    public function report()
    {
        return $this->hasMany('\App\Report');
    }

    public function managedRestaurant()
    {
        return $this->belongsToMany(Restaurant::class);
    }

    public static function getRestaurantManagers($restaurantId)
    {
        $restaurant = Restaurant::find($restaurantId);

        if (!$restaurant) {
            return [];
        }

        $users = User::with('role')->whereManageRestaurantId($restaurant->id)->get()->toArray();

        return $users;

    }

    public static function getManagersRestaurant()
    {
        $currentUser = static::getCurrentUser();

        if (!$currentUser) {
            return false;
        }

        if ($currentUser->hasRole(Role::RESTAURANT_MANAGER)) {
            $restaurant = Restaurant::whereOwnerId($currentUser->id)->first();
        } else if ($currentUser->hasRole([Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER])) {

            $restaurant = @$currentUser->managedRestaurant[0];

        } else {
            $restaurant = false;
        }

        if ($restaurant) {
            return $restaurant;
        }

        return false;

    }

    public function scopeCheckRole($query)
    {
        $currentUser = static::getCurrentUser();

        if (!$currentUser) {
            return false;
        }

        if ($currentUser->hasRole(Role::SUPER_ADMIN)) {
            return $query;
        }

        if ($currentUser->hasRole(Role::RESTAURANT_MANAGER)) {

            $restaurant = User::getManagersRestaurant();

            if ($restaurant) {
                $restaurantId = $restaurant->id;
            } else {
                $restaurantId = -1;
            }

            return $query->whereManageRestaurantId($restaurantId);
        }

        return $query;
    }

    public static function getCurrentUser($throwException = false)
    {
        $user = false;

        $with = ['roles'];
        if (Auth::guest()) {
            try {

                $checker = app(ResourceServer::class);
                $checker->isValidRequest();
                $userId = $checker->getAccessToken()->getSession()->getOwnerId();

                $user = User::with($with)->find($userId);

            } catch (\Exception $e) {
                if ($throwException) {
                    throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
                }
                return false;
            }

        } else {
            $user = User::with($with)->find(Auth::user()->id);
        }
        return $user;
    }

    public function files()
    {
        return $this->morphMany('App\File', 'imageable');
    }

    public function profilePicture()
    {
        $file = $this->morphOne('App\File', 'imageable')->where('category', File::getCategorySlug('user_profile_picture'));
        return $file;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = date('Y-m-d', strtotime($value));
    }

    public function rates()
    {
        return $this->hasMany('\App\Rate');
    }

    /**
     * The users that belong to the restaurants.
     */
    public function favoriteRestaurants()
    {
        return $this->belongsToMany('App\Restaurant', 'favorite_restaurnt')->withTimestamps();
    }

    public static function calculateUserRate($id, $returnRateValue = false, $returnLevel = false)
    {
        $res = Rate::with(['user', 'user.roles'])->where('user_id', $id)->get();

        $rates = $res;
        $total = 0;

        if ($rates->count()) {

            foreach ($rates as $rate) {

                $role = @$rate->user->roles->toArray()[0]['name'];

                switch ($role) {
                    case Role::DINNER;
                        $total += 1;
                    case Role::BLOGGER_FOOD_CRITICS;
                        $total += 2;
                        break;
                    case Role::AUDITOR;
                        $total += 3;
                        break;
                    case Role::AUDITOR_OF_AUDITORS;
                        $total += 10;
                        break;
                }
            }
        }

        $levels = array_pluck(Rate::getLevels(), 'value', 'display_name');

        if ($total < 180) {

            $levels = array_where($levels, function ($key, $value) use ($total) {

                if ($value['from'] <= $total && $value['to'] >= $total) {
                    return true;
                }

                return false;
            });
        } else {
            $levels = end($levels);
        }

        if ($returnRateValue) {
            return $total;
        }

        if ($returnLevel) {
            return array_keys($levels)[0];
        }

        $levels['rate'] = $total;

        return $levels;
    }

    /**
     * Validate Current User owns the thrown object.
     *
     * @param $object
     * @return bool
     */
    public function isOwner($object)
    {
        //If admin return true
        if ($this->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        // If Object is a sub module of restaurant like menu items, gallery etc
        // Check if current user is one of the manages of restaurant>
        if (isset($object->restaurant_id)) {

            $restaurant = Restaurant::find($object->restaurant_id);

            $managers = $restaurant->managers->pluck('id')->toArray();

            $managers[] = $restaurant->owner_id;

            if (in_array($this->id, $managers)) {
                return true;
            }
        }

        if (!isset($object->user_id) && get_class((object)$object) !== User::class) {
            return false;
        }

        if (get_class($object) == User::class) {

            return $this->id == $object->id;
        }

        return $this->id == $object->user_id;
    }

    public function votes()
    {
        return $this->hasMany('\App\Vote');
    }

    /**
     * get e-mail only by user_id
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public function scopeGetEmail($query, $user_id)
    {
        return $query->where('id', $user_id)->pluck('email')->toArray();
    }

    /**
     * get phone only by user_id
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public function scopeGetMobile($query, $user_id)
    {
        return $query->where('id', $user_id)->pluck('phone')->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likesDislikes()
    {
        return $this->hasMany(ImagesSocial::class);
    }

    /**
     * Plucks name only
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public function scopeGetName($query, $user_id)
    {
        return $query->where('id', $user_id)->pluck('name')->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function appliedVacancies()
    {
        return $this->belongsToMany(JobVacancy::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
}
