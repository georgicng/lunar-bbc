<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ComponentLoader extends Facade
{
    public static function getFacadeAccessor()
    {
        return \App\Extensions\Form\AttributeData::class;
        //return \App\Extensions\Form\ComponentLoader::class;
    }

}
