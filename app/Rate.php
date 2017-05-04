<?php

namespace App;

use App\Http\Helpers\SearchableTrait;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use SearchableTrait;

    protected $fillable = ['rate_value', 'review_id', 'type', 'restaurant_id', 'user_id'];

    public static $MUSIC = 1;
    public static $LOOKS_OF_RESTAURANT = 2;
    public static $ACCESSIBILITY = 3;
    public static $TEMPERATURE = 4;
    public static $TASTE = 5;
    public static $CLEAN_FLOORING = 6;
    public static $CLEAN_TABLES = 7;
    public static $CLEAN_ENVIRONMENT = 8;
    public static $DOOR_GREETING = 9;
    public static $WAITER_FRIENDLINESS = 10;
    public static $SPEED_OF_SERVICE = 11;
    public static $WAITERS_KNOWLEDGE_OF_MENU = 12;
    public static $PRESENTATION = 13;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'rates.rate_value' => 10,
            'rates.created_at' => 10,
            'rates.updated_at' => 5,
            'reviews.title' => 10,
            'reviews.description' => 10,
        ],
        'joins' => [
            'reviews' => ['rates.id', 'reviews.rate_id'],
        ]
    ];

    public static $_ratingLevels = [
        ['name' => 'no_star', 'display_name' => 'No Star', 'value' => ['from' => '0', 'to' => '9']],
        ['name' => 'level_one', 'display_name' => 'Level 1', 'value' => ['from' => '10', 'to' => '14']],
        ['name' => 'level_two', 'display_name' => 'Level 2', 'value' => ['from' => '15', 'to' => '24']],
        ['name' => 'level_three', 'display_name' => 'Level 3', 'value' => ['from' => '25', 'to' => '39']],
        ['name' => 'level_four', 'display_name' => 'Level 4', 'value' => ['from' => '40', 'to' => '59']],
        ['name' => 'level_five', 'display_name' => 'Level 5', 'value' => ['from' => '60', 'to' => '79']],
        ['name' => 'level_six', 'display_name' => 'Level 6', 'value' => ['from' => '80', 'to' => '99']],
        ['name' => 'level_seven', 'display_name' => 'Level 7', 'value' => ['from' => '100', 'to' => '119']],
        ['name' => 'level_eight', 'display_name' => 'Level 8', 'value' => ['from' => '120', 'to' => '149']],
        ['name' => 'level_nine', 'display_name' => 'Level 9', 'value' => ['from' => '150', 'to' => '179']],
        ['name' => 'level_ten', 'display_name' => 'Level 10', 'value' => ['from' => '180', 'to' => '180']],
    ];

    public static function getRateKeyValue()
    {

        $types = self::getRatingTypes();

        $return = [];

        foreach ($types as $type) {
            $type = $type['cats'];

            foreach ($type as $t) {
                $return[$t['value']] = $t['display_name'];
            }
        }

        return $return;
    }

    public static function getRatingTypes()
    {
        return [
            [
                'weight' => '20',
                'display_name' => 'Atmosphere',
                'cats' => [
                    ['value' => static::$MUSIC, 'weight' => '25', 'display_name' => 'Music',],
                    ['value' => static::$LOOKS_OF_RESTAURANT, 'weight' => '50', 'display_name' => 'Looks of restaurant'],
                    ['value' => static::$ACCESSIBILITY, 'weight' => '25', 'display_name' => 'Accessibility'],
                ]
            ],
            [
                'weight' => '35',
                'display_name' => 'Food',
                'cats' => [
                    ['value' => static::$PRESENTATION, 'weight' => '30', 'display_name' => 'Presentation'],
                    ['value' => static::$TEMPERATURE, 'weight' => '30', 'display_name' => 'Temperature'],
                    ['value' => static::$TASTE, 'weight' => '40', 'display_name' => 'Taste'],
                ]
            ],
            [
                'weight' => '15',
                'display_name' => 'Cleanness of Restaurant',
                'cats' => [
                    ['value' => static::$CLEAN_FLOORING, 'weight' => '33.3', 'display_name' => 'Clean Flooring'],
                    ['value' => static::$CLEAN_TABLES, 'weight' => '33.3', 'display_name' => 'Clean Table'],
                    ['value' => static::$CLEAN_ENVIRONMENT, 'weight' => '33.4', 'display_name' => 'Clean Environment'],
                ]
            ],
            [
                'weight' => '30',
                'display_name' => 'Service',
                'cats' => [
                    ['value' => static::$DOOR_GREETING, 'weight' => '20', 'display_name' => 'Door greeting'],
                    ['value' => static::$WAITER_FRIENDLINESS, 'weight' => '20', 'display_name' => 'Waiter friendliness'],
                    ['value' => static::$SPEED_OF_SERVICE, 'weight' => '30', 'display_name' => 'Speed of service'],
                    ['value' => static::$WAITERS_KNOWLEDGE_OF_MENU, 'weight' => '30', 'display_name' => 'Waiters knowledge of menu'],
                ]
            ],

        ];
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public static function getLevels()
    {
        return static::$_ratingLevels;
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function review()
    {
        return $this->belongsTo('\App\Review');
    }
}
