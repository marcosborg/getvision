<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'services';

    public const ICON_RADIO = [
        'Aluguer de viaturas' => 'fas fa-car-alt icon',
        'Trabalhar com viatura própria' => 'fas fa-user-tag icon',
        'Stand de viaturas' => 'fas fa-search-dollar icon',
        'Estafetas' => 'fas fa-parachute-box icon',
        'Formação' => 'fas fa-chalkboard-teacher icon',
        'Acessórios' => 'fas fa-shopping-cart icon',
        'Transfers e Tours' => 'fas fa-bus icon',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'text',
        'icon',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
