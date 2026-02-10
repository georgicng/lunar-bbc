<?php
namespace App\Extensions\FieldTypes\RepeaterGroup;

use Lunar\Admin\Support\Synthesizers\AbstractFieldSynth;
use App\Extensions\FieldTypes\RepeaterGroup;

class RepeaterGroupSynth extends AbstractFieldSynth
{
    public static $key = 'lunar_repeater_group_field';

    protected static $targetClass = RepeaterGroup::class;
}
