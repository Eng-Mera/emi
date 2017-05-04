<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 4/25/16
 * Time: 11:09 AM
 */

namespace App\Http\Helpers;


use App\OpeningDay;
use App\Restaurant;
use App\Role;
use App\User;

trait RestaurantTrait
{

    /**
     * Calculate total number of restaurant item.
     *
     * @param $enabled
     * @param $slug
     * @param $item
     * @return int
     */
    public function restaurantsItemsCount($enabled, $slug, $item)
    {
        if ($enabled) {

            $itemRequest = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', \Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $slug . '/' . $item);

            $itemCount = $itemRequest ? $itemRequest->total() : 0;

        } else {
            $itemCount = 0;
        }

        return $itemCount;
    }

    /**
     * Get a list of opening date formatted.
     *
     * @param $enabled
     * @param Restaurant $restaurant
     * @return array
     */
    public function getOpeningDays($enabled, Restaurant $restaurant)
    {
        if ($enabled) {

            $openDays = OpeningDay::where('restaurant_id', $restaurant->id)->orderBy('id', 'desc')->paginate(1);

            $openingDays = OpeningDayTrait::convertToString($openDays);
        } else {
            $openingDays = [];
        }

        return $openingDays;
    }

    /**
     * Get Suggested restaurants
     *
     * @param $flag
     * @param Restaurant $restaurant
     * @return array
     */
    public function getSuggestedRestaurant($flag, Restaurant $restaurant)
    {

        if (!$flag) {
            return [];
        }

        $paging = $this->getDatatablePaging(['id', 'category_name', 'created_at', 'updated_at']);

        $catIds = $restaurant->categories->pluck('id')->toArray();

        if ($catIds) {

            $paging['per_page'] = 7;
            $paging['page'] = 1;
            $paging['filters']['not_in'] = $restaurant->id;
            $paging['filters']['category'] = $catIds;
            $paging['filters']['distance']['value'] = 5;
            $paging['filters']['distance']['latitude'] = $restaurant->latitude;
            $paging['filters']['distance']['longitude'] = $restaurant->longitude;

            $relatedRestaurant = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', \Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant', $paging);

            $suggested = json_decode($relatedRestaurant->toJson())->data;

        } else {
            $suggested = [];
        }

        return $suggested;
    }
}