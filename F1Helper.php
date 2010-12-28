<?php

class F1Helper {
  // 2010 races
  public static function getRaces() {
    return array(0 => "Bahrain", 1 => "Melbourne - Australia",
      2 => "Sepang - Malaysia", 3 => "Shanghai - China",
      4 => "Barcelona - Spain",
      5 => "Monte Carlo - Monaco", 6 => "Istanbul - Turkey",
      7 => "Montreal - Canada", 8 => "Valencia - Europe",
      9 => "Silverstone - Britain", 10 => "Hockenheim - Germany",
      11 => "Budapest - Hungary", 12 => "Spa - Belgium", 13 => "Monza - Italy",
      14 => "Singapore", 15 => "Suzuka - Japan", 16 => "Yeongam - Korea",
      17 => "Interlagos - Brazil", 18 => "Abu Dhabi");
  }

  // Race types
  public static function getTypes() {
    return array(0 => "Practice", 1 => "Qualifying", 2 => "Race");
  }

  // Tyre types
  public static function getTyres() {
    return array(0 => "Option", 1 => "Prime", 2 => "Intermediate", 3 => "Wet");
  }

  // Cars
  public static function getCars() {
    return array(0 => "RBR-Renault", 1 => "McLaren-Macedes",
        2 => "Farrari", 3 => "Macedes-GP", 4 => "Renault",
        5 => "Williams-Cosworth", 6 => "Force India-Macedes",
        7 => "BMW Sauber-Farrari", 8 => "STR-Farrai", 9 => "Lotus-Cosworth",
        10 => "HRT-Cosworth", 11 => "Virgen-Cosworth");
  }

  public static function strtodate($time) {
    return date("d/m-Y G:i",$time);
  }
}

?>
