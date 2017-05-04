<?php

namespace App;

use App\Http\Helpers\SearchableTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'display_name' => 10,
            'description' => 10,
        ]
    ];

    protected static $_roles = [
        ['name' => 'dev-admin', 'display_name' => 'Development Admins', 'description' => 'View/Accept Restaurant Account Application / Approve/Reject Restaurant Claims / Manage restaurants (add, edit, view, delete) / Manage Users: Restaurant Manager/Blogger/Auditor / Add HR feature to Restaurant Manager (Post Vacancy &amp; View applications) / Delete reviews / Assign HTR stars / Manage Job Application (add, edit, view, delete) fields / Manage Job titles (add, edit, view, delete)'],
        ['name' => 'super-admin', 'display_name' => 'Super Admin', 'description' => 'View/Accept Restaurant Account Application / Approve/Reject Restaurant Claims / Manage restaurants (add, edit, view, delete) / Manage Users: Restaurant Manager/Blogger/Auditor / Add HR feature to Restaurant Manager (Post Vacancy &amp; View applications) / Delete reviews / Assign HTR stars / Manage Job Application (add, edit, view, delete) fields / Manage Job titles (add, edit, view, delete)'],
        ['name' => 'restaurant-manager', 'display_name' => 'Restaurant Manager', 'description' => 'View/Edit restaurant details / Manage Restaurant Admins (add, edit, view, delete) / Reply to reviews / Post a vacancy (feature to be added by Super Admin) / View Job Applications (feature to be added by Super Admin) / Claim a restaurant / Manage Reservation Policy /   Manage Menus (add, edit, view, delete)'],
        ['name' => 'restaurant-admin', 'display_name' => 'Restaurant Admin', 'description' => 'Reply to reviews / Post a vacancy / View Job Applications / Manage Reservation Policy / View/Edit restaurant details / Manage Menus (add, edit, view, delete)'],
        ['name' => 'reservation-manager', 'display_name' => 'Reservation Manager', 'description' => 'Accept / Fully'],
        ['name' => 'auditor', 'display_name' => 'Auditor', 'description' => 'Auditors will be able to use all features of the application to search for a restaurant, reserve tables and submit reviews.'],
        ['name' => 'auditor-of-auditors', 'display_name' => 'Auditor of Auditors', 'description' => 'Auditor of the auditors will be able to use all features of the application to search for a restaurant, reserve tables and submit reviews.'],
        ['name' => 'blogger-food-critics', 'display_name' => 'Blogger and Food Critics', 'description' => 'Bloggers and food critics will be able to use all features of the application to search for a restaurant, reserve tables and submit reviews. '],
        ['name' => 'job-seeker', 'display_name' => 'Job Seeker', 'description' => 'Job Seeker Can Search and view posted vacancies , apply for a vacancy and will receive an email with confirmation of their application once submitted '],
        ['name' => 'diner', 'display_name' => 'Diner', 'description' => 'Consumers who use the app to rate, search and review restaurants, they will also be able to reserve tables and book cinema tickets.'],
        ['name' => 'guest', 'display_name' => 'Guest', 'description' => 'Guest user.'],
    ];

    const DEV_ADMIN = 'dev-admin';
    const SUPER_ADMIN = 'super-admin';
    const RESTAURANT_ADMIN = 'restaurant-admin';
    const RESTAURANT_MANAGER = 'restaurant-manager';
    const RESERVATION_MANAGER = 'reservation-manager';
    const AUDITOR = 'auditor';
    const AUDITOR_OF_AUDITORS = 'auditor-of-auditors';
    const BLOGGER_FOOD_CRITICS = 'blogger-food-critics';
    const JOB_SEEKER = 'job-seeker';
    const DINNER = 'diner';
    const GUEST = 'guest';

    protected static $allowedRoles = [
        self::DEV_ADMIN => [self::DEV_ADMIN, self::SUPER_ADMIN, self::RESTAURANT_MANAGER, self::RESTAURANT_ADMIN, self::RESERVATION_MANAGER, self::AUDITOR, self::AUDITOR_OF_AUDITORS, self::BLOGGER_FOOD_CRITICS, self::JOB_SEEKER, self::DINNER],
        self::SUPER_ADMIN => [self::SUPER_ADMIN, self::RESTAURANT_MANAGER, self::RESTAURANT_ADMIN, self::RESERVATION_MANAGER, self::AUDITOR, self::AUDITOR_OF_AUDITORS, self::BLOGGER_FOOD_CRITICS, self::JOB_SEEKER, self::DINNER],
        self::RESTAURANT_MANAGER => [self::RESTAURANT_ADMIN, self::RESERVATION_MANAGER],
    ];

    public static function getAllowedRoles($roleName)
    {
        $roles = self::$allowedRoles;

        if (isset($roles[$roleName])) {
            return $roles[$roleName];
        }

        return [];
    }

    public static function getAdminRouteRoles($concatenated = true)
    {
        $roles =
            [
                \App\Role::SUPER_ADMIN,
                \App\Role::RESTAURANT_MANAGER,
                \App\Role::RESTAURANT_ADMIN,
                \App\Role::RESERVATION_MANAGER,
            ];

        if ($concatenated) {
            $roles = implode('|', $roles);
        }

        return $roles;
    }

    public static function getRoles()
    {
        return static::$_roles;
    }

    public function roleRoutes()
    {
        return $this->belongsToMany('\App\Route');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function can($role_id, $permission_name)
    {
        return false;
    }
}
