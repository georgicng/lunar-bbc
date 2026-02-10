<?php
namespace App\Extensions\FieldTypes\Repeater;

use Lunar\Admin\Support\Synthesizers\AbstractFieldSynth;
use App\Extensions\FieldTypes\Repeater;

class RepeaterSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_repeater_field';

    protected static $targetClass = Repeater::class;
}
