<?php

namespace App\Http\Helpers;

use App\Http\BladeDirectives\BladeDirective;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Lavary\Menu\Menu;


define('HTR_ROLES_SA_RM_RA_RESM', [Role::SUPER_ADMIN, Role::RESTAURANT_MANAGER, Role::RESERVATION_MANAGER, Role::RESTAURANT_ADMIN]);
define('HTR_ROLES_RM_RA_RESM', [Role::RESTAURANT_MANAGER, Role::RESERVATION_MANAGER, Role::RESTAURANT_ADMIN]);
define('HTR_ROLES_RM_RA', [Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN]);
define('HTR_ROLES_SA_RM', [Role::RESTAURANT_MANAGER, Role::SUPER_ADMIN]);

trait MenuTrait
{
    use BladeDirective;

    /**
     * Menu Modules
     * @var array
     */
    protected $_modules = [
        ['nickname' => 'users', 'icon' => 'users', 'name' => 'Users', 'roles' => HTR_ROLES_SA_RM],
        ['nickname' => 'restaurants', 'icon' => 'coffee', 'name' => 'Restaurants', 'roles' => Role::SUPER_ADMIN],
        ['nickname' => 'restaurant_team', 'icon' => 'coffee', 'name' => 'My Restaurant', 'roles' => HTR_ROLES_RM_RA],
        ['nickname' => 'reports', 'icon' => 'warning', 'name' => 'Reports', 'roles' => Role::SUPER_ADMIN],
        ['nickname' => 'jobs', 'icon' => 'bell', 'name' => 'Jobs', 'roles' => Role::SUPER_ADMIN],
        ['nickname' => 'movies', 'icon' => 'film', 'name' => 'Movies', 'roles' => Role::SUPER_ADMIN],
        ['nickname' => 'admin_reviews', 'icon' => 'reply', 'name' => 'Admin Reviews', 'roles' => Role::SUPER_ADMIN],
        ['nickname' => 'reservations', 'icon' => 'cart-arrow-down', 'name' => 'Reservations', 'roles' => [Role::SUPER_ADMIN, Role::RESERVATION_MANAGER, Role::RESTAURANT_MANAGER]]
    ];

    /**
     * Module Items
     * @var array
     */
    protected $_items = [];

    /**
     * Make sure current user can access the specified module/item
     * @param $roles
     * @return bool
     */
    public function authorizeOwner($roles)
    {
        $user = User::getCurrentUser();

        if (!$user) {
            return false;
        }

        $roles = (array)$roles;

        if ($user->hasRole(Role::SUPER_ADMIN) && in_array(Role::SUPER_ADMIN, $roles)) {
            return true;
        } else if ($user->hasRole(Role::DEV_ADMIN) && in_array(Role::DEV_ADMIN, $roles)) {
            return true;
        } else {

            $restaurant = User::getManagersRestaurant();

            if ($restaurant && $user->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get list of available items
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Append new item to specified module.
     *R
     * @param $moduleName       The Name of module
     * @param array $item Item to be appended.
     */
    public function addItem($moduleName, array $item)
    {
        $this->_items[$moduleName][] = $item;
    }

    /**
     * Append items to modules
     */
    public function createItems()
    {
        $this->users();
        $this->restaurants();
        $this->adminReviews();
        $this->reports();
        $this->jobs();
        $this->movies();
        $this->reservations();
    }

    /**
     * Users Module
     */
    private function users()
    {
        $moduleName = 'users';

        $this->addItem($moduleName, ['title' => ('Users'), 'url' => url('admin/user'), 'icon' => 'users', 'roles' => HTR_ROLES_SA_RM_RA_RESM]);
        $this->addItem($moduleName, ['title' => ('Roles'), 'url' => url('admin/role'), 'icon' => 'user-plus', 'roles' => Role::DEV_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Permissions'), 'url' => url('admin/permission'), 'icon' => 'user-secret', 'roles' => Role::DEV_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Flush Routes'), 'url' => url('admin/flush-routes'), 'icon' => 'bolt', 'roles' => Role::DEV_ADMIN]);
    }

    /**
     * Restaurants Modules
     */
    private function restaurants()
    {
        //Super admin
        $moduleName = 'restaurants';

        $this->addItem($moduleName, ['title' => ('Restaurants'), 'url' => url('admin/restaurant'), 'icon' => 'coffee', 'roles' => Role::SUPER_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Cities'), 'url' => url('admin/city'), 'icon' => 'road', 'roles' => Role::SUPER_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Categories'), 'url' => url('admin/category'), 'icon' => 'fire', 'roles' => Role::SUPER_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Dish Category'), 'url' => url('admin/dish-category'), 'icon' => 'fire', 'roles' => Role::SUPER_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Facilities'), 'url' => url('admin/facility'), 'icon' => 'rocket', 'roles' => Role::SUPER_ADMIN]);
        $this->addItem($moduleName, ['title' => ('Claims'), 'url' => url('admin/claim'), 'icon' => 'question', 'roles' => Role::SUPER_ADMIN]);

        //Restaurant team
        $moduleName = 'restaurant_team';
        $restaurant = User::getManagersRestaurant();
        $restaurant_slug = @$restaurant->slug;

        $this->addItem($moduleName, ['title' => ('My Restaurant'), 'url' => url('admin/restaurant/' . $restaurant_slug . '/edit'), 'icon' => 'coffee', 'roles' => HTR_ROLES_RM_RA]);
    }

    /**
     * Admin Review Module
     */
    private function adminReviews()
    {
        $moduleName = 'admin_reviews';

        $this->addItem($moduleName, ['title' => ('Admin Reviews'), 'url' => url('admin/admin-review'), 'icon' => 'reply', 'roles' => Role::SUPER_ADMIN]);
    }

    /**
     * Reports Module
     */
    private function reports()
    {
        $moduleName = 'reports';

        $this->addItem($moduleName, ['title' => ('Reports'), 'url' => url('admin/report'), 'icon' => 'warning', 'roles' => Role::SUPER_ADMIN]);
    }

    /**
     * Jobs Module
     */
    private function jobs()
    {
        $moduleName = 'jobs';

        $this->addItem($moduleName, ['title' => ('Job Titles'), 'url' => url('admin/job-title'), 'icon' => 'bell', 'roles' => Role::SUPER_ADMIN]);
    }

    /**
     * Movies Module
     */
    private function movies()
    {
        $moduleName = 'movies';

        $this->addItem($moduleName, ['title' => ('Movies'), 'url' => url('admin/movie'), 'icon' => 'film', 'roles' => Role::SUPER_ADMIN]);
    }

    /**
     * Reservations Module
     */
    private function reservations()
    {
        $restaurant = User::getManagersRestaurant();

        $moduleName = 'reservations';

        if ($restaurant && !$restaurant->reservable_online) {
            $this->addItem($moduleName, ['title' => ('<p><i class="fa fa-warning"></i>The Reservation needs to be<br/>enabled by super admin</p>'), 'url' => '#', 'icon' => '', 'roles' => [Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER, Role::RESTAURANT_MANAGER]]);
            return false;
        }

        $this->addItem($moduleName, ['title' => ('Reservations'), 'url' => url('admin/reservation'), 'icon' => 'cart-plus', 'roles' => [Role::SUPER_ADMIN, Role::RESERVATION_MANAGER, Role::RESTAURANT_MANAGER]]);
        $this->addItem($moduleName, ['title' => ('Coupons'), 'url' => url('admin/coupon'), 'icon' => 'diamond', 'roles' => [Role::SUPER_ADMIN, Role::RESERVATION_MANAGER, Role::RESTAURANT_MANAGER]]);
    }

    /**
     * Return a list of modules
     * @return array
     */
    public function getModules()
    {
        return $this->_modules;
    }

    /**
     * Check if current url must be active.
     *
     * @param $uri The url to check.
     */
    public function isActive($uri)
    {
        $request = app(Request::class);

        $request->url == $uri;

    }

    /**
     * Create and append menu
     */
    public function loadMenu()
    {
        $this->createItems();

        $menu = new Menu();

        $menu->make('backend_sidebar', function ($menu) {

            foreach ($this->getModules() as $module) {

                $nickname = $module['nickname'];
                $name = @$module['name'];
                $icon = @$module['icon'];
                $roles = @$module['roles'];
                $error = @$module['error'];

                if (!$this->authorizeOwner($roles)) {
                    continue;
                }

                $menu
                    ->add($name, ['class' => 'treeview', 'nickname' => $nickname, 'error' => $error])
                    ->prepend('<i class="fa fa-' . $icon . '"></i>')
                    ->append('<i class="fa fa-angle-left pull-right"></i>')
                    ->link->href('#');

                foreach ((array)@$this->getItems()[$nickname] as $item) {

                    $url = @$item['url'];
                    $icon = @$item['icon'];
                    $roles = @$item['roles'];

                    $isActive = $this->isActive($url);

                    if (!$this->authorizeOwner($roles)) {
                        continue;
                    }

                    $subItem = $menu
                        ->get($nickname)
                        ->add($item['title'], ['url' => $url])
                        ->prepend('<i class="fa fa-' . @$icon . '"></i>');

                    if ($isActive) {
                        $menu->get($nickname)->active();
                        $subItem->active();
                    }

                }
            }

        });

    }
}