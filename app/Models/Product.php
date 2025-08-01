<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use function PHPSTORM_META\map;

class Product extends Model
{
    use HasSlug;
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'inStock',
        'price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImages::class);
    }
    
    public function product_comments()
    {
        return $this->hasMany(ProductComments::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function product_diameter()
    {
        return $this->belongsTo(ProductDiameter::class);
    }

    public function product_fit_diameter()
    {
        return $this->belongsTo(ProductFitDiameter::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function favorite()
    {
        return $this->hasMany(FavoriteProducts::class);
    }


    //filter logic for price or categories or brands 

    public function scopeFiltered(Builder $quary)  {
        $quary->when(request('brands'), function (Builder $q)  {
            $q->whereIn('brand_id',request('brands'));

        })->when(request('categories'), function (Builder $q)  {
            $q->whereIn('category_id',request('categories'));
            
        })->when(request('product_diameters'), function (Builder $q)  {
            $q->whereIn('diameter_of_disk',request('product_diameters'))->orWhereIn('drill_diameter', request('product_diameters'));
            
        })->when(request('product_fit_diameters'), function (Builder $q)  {
            $q->whereIn('diameter_of_fit',request('product_fit_diameters'));
            
        })->when(request('prices'), function(Builder $q)  {
            $q->whereBetween('price',[
                request('prices.from',0),
                request('prices.to', 100000),
            ]);
        })->when(request('sort_by'), function (Builder $q) {
            switch (request('sort_by')) {
                case 'Рейтинг':
                    $q->orderBy('rating', 'desc');
                    break;
                case 'Нові':
                    $q->orderBy('id', 'desc'); // newest products first
                    break;
                case 'Низька ціна':
                    $q->orderBy('price', 'asc');
                    break;
                case 'Висока ціна':
                    $q->orderBy('price', 'desc');
                    break;
                default:
                    // optional: set a default sorting
                    $q->orderBy('id', 'asc');
                    break;
            }
        
        });
        
    }
}