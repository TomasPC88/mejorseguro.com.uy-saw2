<?php

namespace App\Models;

use App\Models\Traits\Mediable;
use App\Models\Traits\Translable;

class Portada extends BaseModel
{
    use Mediable, Translable;

    protected $fillable = [
        'url', 'target', 'active', 'pos'
    ];

    protected $casts = [
        'active:boolean',
        'target:boolean'
    ];

    protected $appends = ['first_image'];

    //Arreglo de atributos para el manejo de eventos en el Subscriber de Eloquent. que se lanza cuando se elimine el modelo
    protected $events = [
        //Relacion, Metodo QueryBuilder
        'medias' => [
            'constraint' => false,
            'operation' => 'delete'
        ]
    ];

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = w2_set_checkbox($value);
    }

    public function setTargetAttribute($value)
    {
        $this->attributes['target'] = w2_set_checkbox($value);
    }
}
