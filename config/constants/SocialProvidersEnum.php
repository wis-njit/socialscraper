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

        foreach (self::getConstants() as $constant){
            if($constant === $value)
                return true;
        }
        return false;
    }

    public static function getByValue($inputVal){

        foreach ( self::getConstants() as $name => $value ){
            if ( $value === $inputVal )
                return $name;
        }
        return null;
    }

    public static function getValue($inputName){

        foreach ( self::getConstants() as $name => $value ){
            if ( $name === strtoupper($inputName) )
                return $value;
        }
        return null;
    }

    private static function getConstants(){
        $r = new ReflectionClass(self::class);
        return $r->getConstants();
    }
}