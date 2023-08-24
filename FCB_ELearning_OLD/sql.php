<?php 
class sql {
    private static $con1 = null;
    private static $con2 = null;
    private static $cat = null;
    protected static function open($conprop) {
        return new PDO($conprop["constring"], $conprop["uid"], $conprop["pass"]);
    }
    public static function con1() {
        if (self::$con1 == null)
           self::$con1 = self::open(FEED17);
        return self::$con1;
    }
    public static function netlinkz() {
        if (self::$con2 == null)
           self::$con2 = self::open(NETLINKZ);
        return self::$con2;
    }
    public static function cat() {
        if (self::$cat == null)
            self::$cat = self::open(CAT);
        return self::$cat;
    }
    public static function assoc() {return PDO::FETCH_ASSOC;}
}
 