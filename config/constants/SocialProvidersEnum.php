<?php

namespace config\constants;
use ReflectionClass;

abstract class SocialProvidersEnum
{
    const GOOGLE = 'google';
    const FACEBOOK = 'facebook';
    const TWITTER = 'twitter';
    const INSTAGRAM = 'instagram';

    public static function isValidValue($value){
        $r = new ReflectionClass(self::class);
        $constants = $r->getConstants();
        foreach ($constants as $constant){
            if($constant === $value)
                return true;
        }
        return false;
    }

    public static function getByValue($inputVal){
        $r = new ReflectionClass(self::class);
        $constants = $r->getConstants();
        foreach ( $constants as $name => $value ){
            if ( $value === $inputVal )
                return $name;
        }
        return null;
    }
}