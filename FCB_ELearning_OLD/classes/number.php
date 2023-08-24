<?php
namespace classes;

class number {
  public static function ordinal($num) {
    $numtxt = (string)$num;
    $len = strlen($numtxt);

    $newnum = $numtxt;;
    
    if ($len > 2) {
      $newnum = $numtxt[$len-2].$numtxt[$len-1];
    }
    if ($newnum == 1) {
      return $numtxt.'st';
    } else if ($newnum == 2) {
      return $numtxt.'nd';
    } else if ($newnum == 3) {
      return $numtxt.'rd';
    } else if (
      $newnum == 11 ||
      $newnum == 4  ||
      $newnum == 5  ||
      $newnum == 6  ||
      $newnum == 7  ||
      $newnum == 8  ||
      $newnum == 9  ||
      $newnum == 10 ||
      $newnum == 0  ||
      $newnum == 13 ||
      $newnum == 12
    ) {
      return $numtxt.'th';
    }
    if ($numtxt[$len-1] == 1) {
      return $numtxt.'st';
    }
    else if ($numtxt[$len-1] == 2) {
      return $numtxt.'nd';
    } else if ($numtxt[$len-1] == 3) {
      return $numtxt.'rd';
    } else {
      return $numtxt.'th';
    }
  }
}