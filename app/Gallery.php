<?php

namespace App;

use App\Http\Helpers\SearchableTrait;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{

    use SearchableTrait;

    /*
     * For Translation
    */
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name','description'];

    /********************/
    
    protected $fillable = [
        'user_id', 'file_id', 'restaurant_id', 'name', 'slug', 'description'
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'gallery_translations.name' => 10,
            'gallery_translations.description' => 10,
        ],
        'joins' => [
            'gallery_translations' => ['galleries.id', 'gallery_translations.gallery_id'],
        ],
        'groupBy' => ['galleries.id']

    ];


    public function file()
    {
        return $this->morphMany('App\File', 'imageable')->where('category', File::getCategorySlug('restaurant_gallery'));
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
