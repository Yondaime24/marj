<?php 
namespace classes;

class Date {
  public static function GetDate() {
    return date("Y-m-d H:i:s");
  }
  public static function Date() {
    return date("Y-m-d");
  }
  public static function rem($current_date, $target_date, $limit) {
    /* checking for the remaining time  */
    $date = [
      "day" => 0,
      "hour" => 0,
      "min" => 0,
      "sec" => 0
    ];
    $diff = $current_date - $target_date;
    $sec = $limit - $diff;
    if ($sec <= 0) return $date;
    $tmp = 0;
    $day = 0;
    $day = (int)($sec / 86400);
    $tmp = $sec % 86400;
    $date["day"] = $day;
    $sec = $tmp;
    
    $day = (int)($sec / 3600);
    $tmp = $sec % 3600;
    $date["hour"] = $day;
    $sec = $tmp;
    
    $day = (int)($sec / 60);
    $tmp = $sec % 60;
    $date["min"] = $day;
    $sec = $tmp;
    $date["sec"] = $sec;
    return $date;
  }
  public static function spentFull($sec) {
    $diff = $sec;
    $len = count(self::$sec);
    $i = 0;
    $t = '';
    while ($i < $len) {
      if ($diff >= self::$sec[$i]) {
        $value = (int)($diff / self::$sec[$i]);
        $label = ($value > 1) ? self::$info[$i] . "s" : self::$info[$i];
        $diff = $diff % self::$sec[$i];
        $t .= ' '.$value.' '.$label;
      }
      $i++;
    }
    $l = $diff > 1 ? " Seconds" : " Second";
    $s = '';
    if ($diff != 0)
      $s = ' '.$diff.$l;
    return $t.$s;
  }
  public static function spentShort($sec) {
    $diff = $sec;
    $len = count(self::$sec);
    $i = 0;
    $t = '';
    while ($i < $len) {
      if ($diff >= self::$sec[$i]) {
        $value = (int)($diff / self::$sec[$i]);
        $label = ($value > 1) ? self::$info[$i] . "s" : self::$info[$i];
        $diff = $diff % self::$sec[$i];
        $t .= $value.':';
      }
      $i++;
    }
    $s = '';
    if ($diff != 0)
      $s = $diff;
    return $t.$s;
  }
  public static function Spent($sec) {
    $diff = $sec;
    $len = count(self::$sec);
    $i = 0;
    while ($i < $len) {
      if ($diff >= self::$sec[$i]) {
        $value = (int)($diff / self::$sec[$i]);
        $label = ($value > 1) ? self::$info[$i] . "s" : self::$info[$i];
        return $value.' '.$label;
      }
      $i++;
    }
    $l = $diff > 1 ? "Seconds" : "Second";
    return $diff." ".$l;
  }
  private static $sec = [31536000, 2592000, 604800,86400, 3600, 60];
  private static $info = ['Year', 'Month', 'Week' ,'Day', 'Hour', 'Minute'];
  private static $resp = [];
  public static function Ago($date) {
    $now = time();
    $pass = strtotime($date);
    $diff = $now - $pass;
    $len = count(self::$sec);
    $i = 0;
    while ($i < $len) {
      if ($diff >= self::$sec[$i]) {
        $value = (int)($diff / self::$sec[$i]);
        $label = ($value > 1) ? self::$info[$i] . "s" : self::$info[$i];
        return $value.' '.$label;
      }
      $i++;
    }
    // $l = $diff > 1 ? "Seconds" : "Second";
    // return $diff." ".$l;
    return 'Just now';
  }
  public static function elapsed($date) {
    $now = time();
    $pass = strtotime($date);
    $diff = $pass - $now;
    $len = count(self::$sec);
    $i = 0;
    $md = 0.0;
    while ($i < $len) {
      $tmp = $diff / self::$sec[$i];
      $md = $diff % self::$sec[$i];
      $diff = $md;
      self::$resp[self::$info[$i]] = (int)$tmp;
      $i++;
    }
    if ($diff != 0)
      $tmp = $diff / $md;
    self::$resp['sec'] = (int)$md;

    self::$resp['passed'] = $now > $pass ? '1' : '0';
    return self::$resp;
  }
  public static function getElapsed($started_date, $endDateFinished) {
    $now = strtotime($started_date);
    $pass = strtotime($endDateFinished);
    $diff = $pass - $now;
    $len = count(self::$sec);
    $i = 0;
    $md = 0.0;
    while ($i < $len) {
      $tmp = $diff / self::$sec[$i];
      $md = $diff % self::$sec[$i];
      $diff = $md;
      self::$resp[self::$info[$i]] = (int)$tmp;
      $i++;
    }
    if ($diff != 0)
      $tmp = $diff / $md;
    self::$resp['sec'] = (int)$md;

    self::$resp['passed'] = $now > $pass ? '1' : '0';
    return self::$resp;
  }
}