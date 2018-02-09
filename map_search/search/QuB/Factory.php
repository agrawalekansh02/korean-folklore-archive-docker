<?php

/**
 * Factory -- query factory.
 * 
 * API should be accessed from this class
 */

namespace QuB;

class Factory {
    public static function select() {
        return new SelectQuery(func_get_args());
    }
}