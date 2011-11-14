<?php

class F1Helper {

  // 2010 races
  public static function getRaces($year) {
    switch($year) {
    case 2010:
      return array(0 => "Bahrain",
                   1 => "Melbourne - Australia",
                   2 => "Sepang - Malaysia",
                   3 => "Shanghai - China",
                   4 => "Barcelona - Spain",
                   5 => "Monte Carlo - Monaco",
                   6 => "Istanbul - Turkey",
                   7 => "Montreal - Canada",
                   8 => "Valencia - Europe",
                   9 => "Silverstone - Britain", 
                   10 => "Hockenheim - Germany",
                   11 => "Budapest - Hungary", 
                   12 => "Spa - Belgium",
                   13 => "Monza - Italy",
                   14 => "Singapore", 
                   15 => "Suzuka - Japan", 
                   16 => "Yeongam - Korea",
                   17 => "Interlagos - Brazil", 
                   18 => "Abu Dhabi");
    case 2011:
      return array(0 => "Melbourne - Australia",
                   1 => "Kuala Lumpur - Malaysia",
                   2 => "Shanghai - China",
                   3 => "Istanbul - Turkey",
                   4 => "Catalunya - Spain",
                   5 => "Monte Carlo - Monaco",
                   6 => "Montreal - Canada",
                   7 => "Valencia - Europe",
                   8 => "Silverstone - Great Britain",
                   9 => "Nürburgring - Germany",
                   10 => "Budapest - Hungary",
                   11 => "Spa-Francorchamps - Belgium",
                   12 => "Monza - Italy",
                   13 => "Singapore",
                   14 => "Suzuka - Japan",
                   15 => "Yeongam - Korea",
                   16 => "New Delhi - India",
                   17 => "Yas Marina Circuit - Abu Dhabi",
                   18 => "Sao Paulo - Brazil");
    }
  }

  // Race types
  public static function getTypes() {
    return array(0 => "Practice", 1 => "Qualifying", 2 => "Race", 3 => "TimeTrial");
  }

  public static function getStandardType() {
    return 3;
  }

  // Tyre types
  public static function getTyres() {
    return array(0 => "Option", 1 => "Prime", 2 => "Intermediate", 3 => "Wet");
  }

  // Cars
  public static function getCars($year) {
    switch($year) {
    case 2010:
      return array(0 => "RBR-Renault", 
                   1 => "McLaren-Mercedes",
                   2 => "Ferrari", 
                   3 => "Mercedes-GP", 
                   4 => "Renault",
                   5 => "Williams-Cosworth", 
                   6 => "Force India-Mercedes",
                   7 => "BMW Sauber-Ferrari", 
                   8 => "STR-Ferrari", 
                   9 => "Lotus-Cosworth",
                   10 => "HRT-Cosworth", 
                   11 => "Virgin-Cosworth", 
                   12 => "TimeTrial car");
    case 2011:
      return array(0 => "RBR-Renault", 
                   1 => "McLaren-Mercedes",
                   2 => "Ferrari", 
                   3 => "Mercedes", 
                   4 => "Renault",
                   5 => "Force India-Mercedes", 
                   6 => "Sauber-Ferrari",
                   7 => "STR-Ferrari", 
                   8 => "Williams-Cosworth", 
                   9 => "Lotus-Renault",
                   10 => "HRT-Cosworth", 
                   11 => "Virgin-Cosworth", 
                   12 => "TimeTrial car");
    }
  }

  public static function getStandardCar() {
    return 12;
  }

  public static function strtodate($time) {
    return date("d/m-Y G:i", $time);
  }

  /**
   * Return a formatted time string based on a time in milliseconds.
   * @param type $time
   * @return type 
   */
  public static function getTimeString($time) {
    $minutes = $time / 60000;
    $seconds = ($time % 60000) / 1000;
    $split_seconds = ($time % 1000);
    return sprintf('%d:%02d.%03d', $minutes, $seconds, $split_seconds);
  }

  /**
   * Returns the time in milliseconds
   * @param type $time
   * @return type 
   */
  public static function parseTimeString($time) {
    if ( preg_match('/^(\d+):(\d{2})\.(\d{3})$/', $time, $regs) ) {
      if ( ((int)$regs[2]) >= 60 ) {
        // Only 60 seconds in a minute
        return -1;
      }
      $ms = (int)$regs[1] * 60000;
      $ms += (int)$regs[2] * 1000;
      $ms += (int)$regs[3];
      return $ms;
    } else {
      return -1;
    }
  }

}

?>
