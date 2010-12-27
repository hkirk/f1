<?php

include("../includes/database/database.php");
include("../includes/database/SQL.php");
include("../includes/database/MySQL.php");
include("../includes/TimeUtils.php");

$db = new MySQL();

/**
 * MySQL:
 * CREATE TABLE `DBNAME`.`f1_track_times` (
    `tid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `track` INT( 5 ) NOT NULL ,
    `type` INT( 5 ) NOT NULL ,
    `tyre` INT( 5 ) NOT NULL ,
    `name` VARCHAR( 20 ) NOT NULL ,
    `time` VARCHAR( 20 ) NOT NULL ,
    `date` INT( 10 ) NOT NULL
    ) ENGINE = MYISAM
 */

// 2010 races
$races = array(0 => "Bahrain", 1 => "Melbourne - Australia",
    2 => "Sepang - Malaysia", 3 => "Shanghai - China", 4 => "Barcelona - Spain",
    5 => "Monte Carlo - Monaco", 6 => "Istanbul - Turkey",
    7 => "Montreal - Canada", 8 => "Valencia - Europe",
    9 => "Silverstone - Britain", 10 => "Hockenheim - Germany",
    11 => "Budapest - Hungary", 12 => "Spa - Belgium", 13 => "Monza - Italy",
    14 => "Singapore", 15 => "Suzuka - Japan", 16 => "Yeongam - Korea",
    17 => "Interlagos - Brazil", 18 => "Abu Dhabi");

// Race types
$type = array(0 => "Practice", 1 => "Qualifying", 2 => "Race");

// Tyre types
$tyre = array(0 => "Option", 1 => "Prime", 2 => "Intermediate", 3 => "Wet");

$sql = "";
$message = "";

$validateTime = false;
if (isset($_POST['time'])) {
  $tmp = explode(":", $_POST['time']);
  if (sizeof($tmp) == 2) {
    $m = $tmp[0];
    $tmp = explode(".", $tmp[1]);
    if (sizeof($tmp) == 2) {
      $s = $tmp[0];
      $ms = $tmp[1];
      if (strlen($m) == 1 && strlen($s) == 2 && strlen($ms) == 3) {
        $validateTime == true;
      }
    }
  }
  if (!$validateTime) {
    $message = "Time format m:ss:mmm";
  }
}
if ($validateTime && isset($_POST['name'])) {
  $error = false;
  $sql = "INSERT INTO f1_track_times VALUES ('', '" . $_POST['track']
    . "', '" . $_POST['type'] . "', '" . $_POST['tyre'] . $_POST['name']
    . "', '" . $_POST['time'] . "', " . time() . ")";
  $result = $db->sql_query($sql);
  if (!$result) {
    $error = true;
  }
 }

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>F1 Data</title>
    </head>
    <body>
      <div class="input">
        <h1>Report lap time</h1><br /><br />
        <?php
          // Print messages
          echo "<b>". $message . "</b><br/><br />";

          if (isset($error) && $error) {
            echo $db->sql_failed($sql, __LINE__, __FILE__, true);
          } else if (isset($error) && !$error) {
            echo "Value inserted";
          }

        ?>
        <form method="post" action="./f1.php">
          <table>
            <tr>
              <td>Race:</td>
              <td>
                <select name="track">
                  <?
                  for ($i=0; $i < sizeof($races); $i++) {
                    echo "<option value=\"" . $i . "\">". $races[$i] . "</option>\n";
                  }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Type:</td>
              <td>
                <select name="type">
                  <?
                  for ($i=0; $i < sizeof($type); $i++) {
                    echo "<option value=\"" . $i . "\">". $type[$i] . "</option>\n";
                  }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Tyre:</td>
              <td>
                <select name="tyre">
                  <?
                  for ($i=0; $i < sizeof($tyre); $i++) {
                    echo "<option value=\"" . $i . "\">". $tyre[$i] . "</option>\n";
                  }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Name:</td>
              <td><input type="text" name="name" /></td>
            </tr>
            <tr>
              <td>Time:</td>
              <td><input type="text" name="time" /></td>
            </tr>
           <tr>
             <td colspan="2" style="text-align: right;">
               <input type="submit" title="Submit" />
             </td>
           </tr>
          </table>
        </form>
      </div>
      <div class="present">
        <table style="width:80%;">
          <thead style="text-align: left;">
            <tr>
              <th>Name</th>
              <th>Track</th>
              <th>Time</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM f1_track_times ORDER BY track ASC, time DESC";
            $result = $db->sql_query($sql);
            while($row = mysql_fetch_assoc($result)) {
              echo "<tr><td>" . $row['name'] . "</td><td>"
                . $races[$row['track']] . "</td><td>" . $row['time']
                . "</td><td>" . TimeUtils::strtodate($row['date'])
                . "</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </body>
</html>
