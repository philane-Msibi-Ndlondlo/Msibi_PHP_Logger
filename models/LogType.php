<?php

namespace Msibi_PHP;

abstract class LogType {

    const LOG = 0;
    const ERROR = 1;

    public static function getName(int $flag) {
        
        switch ($flag) {
            case self::LOG: return "LOG";
            case self::ERROR: return "ERROR";
            default: return  '';
        }
    }

}
