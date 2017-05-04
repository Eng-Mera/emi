<?php namespace App;

use App\Http\Helpers\SearchableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use SearchableTrait;

    /**
     * @const String
     */
    const PERM_VIEW_RESERVATION = 'view-reservation';

    /**
     * @var String
     */
    const PERM_CHANGE_RESERVATION = 'change-reservation';

    /**
     * @var String
     */
    const PERM_RESCHEDULE_RESERVATION = 'reschedule-reservation';

    /**
     * @var String
     */
    const PERM_CANCEL_RESERVATION = 'cancel-reservation';

    /**
     * @var String
     */
    const PERM_LIST_RESERVATIONS = 'list-reservations';

    /**
     * @var String
     */
    const PERM_LIST_RESTAURANT_RESERVATIONS = 'list-restaurant-reservations';

    /**
     * @var String
     */
    const PERM_RECEIVE_NOTIFICATION = 'reservation-receive-notification';

    /**
     * @var String
     */
    const PERM_MAKE_RESERVATION_FOR_OTHER_USER = 'make-reservation-for-other-user';

    /**
     * @var String
     */
    const PERM_VIEW_USER_RELATED = 'view-user-related';

    protected $searchable = [
        'columns' => [
            'permissions.name' => 10,
            'permissions.display_name' => 10,
            'permissions.description' => 10,
        ]
    ];

    protected static $_permissions = [

        'view-restaurant-account-application' => ['name' => 'view-restaurant-account-application', 'display_name' => 'View Restaurant Account Application'],
        'accept-restaurant-account-application' => ['name' => 'accept-restaurant-account-application', 'display_name' => 'Accept Restaurant Account Application'],

        /**
         * Restaurant
         */
        'create-restaurant' => ['name' => 'create-restaurant', 'display_name' => 'Create Restaurant'],
        'view-restaurant' => ['name' => 'view-restaurant', 'display_name' => 'View Restaurant'],
        'update-restaurant' => ['name' => 'update-restaurant', 'display_name' => 'Update Restaurant'],
        'delete-restaurant' => ['name' => 'delete-restaurant', 'display_name' => 'Delete Restaurant'],
        'list-restaurants' => ['name' => 'list-restaurants', 'display_name' => 'List Restaurant'],
        'claim-restaurant' => ['name' => 'claim-restaurant', 'display_name' => 'Claim Restaurant'],

        /**
         * User
         */
        'create-user' => ['name' => 'create-user', 'display_name' => 'Create User'],
        'view-user' => ['name' => 'view-user', 'display_name' => 'View User'],
        'update-user' => ['name' => 'update-user', 'display_name' => 'Update User'],
        'delete-user' => ['name' => 'delete-user', 'display_name' => 'Delete User'],

        /**
         * HR Features
         */
        'add-hr-feature' => ['name' => 'add-hr-feature', 'display_name' => 'Can add HR Feature'],
        'remove-hr-feature' => ['name' => 'remove-hr-feature', 'display_name' => 'Can remove HR Feature'],

        /**
         * Reviews
         */
        'create-review' => ['name' => 'create-review', 'display_name' => 'Create Review'],
        'read-review' => ['name' => 'read-review', 'display_name' => 'Read Review'],
        'update-review' => ['name' => 'update-review', 'display_name' => 'Update Review'],
        'delete-review' => ['name' => 'delete-review', 'display_name' => 'Delete Review'],

        /**
         * Replies to Reviews
         */
        'create-reply' => ['name' => 'create-reply', 'display_name' => 'Create Reply'],
        'read-reply' => ['name' => 'read-reply', 'display_name' => 'Read Reply'],
        'update-reply' => ['name' => 'update-reply', 'display_name' => 'Update Reply'],
        'delete-reply' => ['name' => 'delete-reply', 'display_name' => 'Delete Reply'],

        /**
         * Job Application
         */
        'create-job-application' => ['name' => 'create-job-application', 'display_name' => 'Create Job Application'],
        'read-job-application' => ['name' => 'read-job-application', 'display_name' => 'Read Job Application'],
        'update-job-application' => ['name' => 'update-job-application', 'display_name' => 'Update Job Application'],
        'delete-job-application' => ['name' => 'delete-job-application', 'display_name' => 'Delete Job Application'],

        /**
         * Reservation Policy
         */
        'create-reservation-policy' => ['name' => 'create-reservation-policy', 'display_name' => 'Create Reservation Policy'],
        'read-reservation-policy' => ['name' => 'read-reservation-policy', 'display_name' => 'Read Reservation Policy'],
        'update-reservation-policy' => ['name' => 'update-reservation-policy', 'display_name' => 'Update Reservation Policy'],
        'delete-reservation-policy' => ['name' => 'delete-reservation-policy', 'display_name' => 'Delete Reservation Policy'],

        /**
         * Restaurant Menu
         */
        'create-restaurant-menu' => ['name' => 'create-restaurant-menu', 'display_name' => 'Create Restaurant Menu'],
        'read-restaurant-menu' => ['name' => 'read-restaurant-menu', 'display_name' => 'Read Restaurant Menu'],
        'update-restaurant-menu' => ['name' => 'update-restaurant-menu', 'display_name' => 'Update Restaurant Menu'],
        'delete-restaurant-menu' => ['name' => 'delete-restaurant-menu', 'display_name' => 'Delete Restaurant Menu'],

        /**
         * Job Titles
         */
        'create-job-title' => ['name' => 'create-job-title', 'display_name' => 'Create Job Title'],
        'read-job-title' => ['name' => 'read-job-title', 'display_name' => 'Read Job Title'],
        'update-job-title' => ['name' => 'update-job-title', 'display_name' => 'Update Job Title'],
        'delete-job-title' => ['name' => 'delete-job-title', 'display_name' => 'Delete Job Title'],
    ];

    public static function getPermissions()
    {
        return static::$_permissions;
    }

    public function permissionRoutes()
    {
        return $this->belongsToMany('\App\Route');
    }
}
