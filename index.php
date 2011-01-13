<?php

include("F1Helper.php");
include("Content.php");

include("lib/database.php");
include("lib/SQL.php");
include("lib/MySQL.php");


$db = new MySQL();

/**
 * MySQL:
 * CREATE TABLE `DBNAME`.`f1_track_times` (
    `tid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `track` INT( 5 ) NOT NULL ,
    `type` INT( 5 ) NOT NULL ,
    `tyre` INT( 5 ) NOT NULL ,
    `car` INT( 5 ) NOT NULL, 
    `name` VARCHAR( 20 ) NOT NULL ,
    `time` VARCHAR( 20 ) NOT NULL ,
    `date` INT( 10 ) NOT NULL
    ) ENGINE = MYISAM
 */


$sql = "";
$message = "";

// Not "good" code style, but working
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
        $validateTime = true;
      }
    }
  }
  if (!$validateTime) {
    $message = "Time format m:ss.tht";
  }
}
if ($validateTime && isset($_POST['name'])) {
  $error = false;
  $sql = "INSERT INTO f1_track_times VALUES ('', '" . $_POST['track']
    . "', '" . $_POST['type'] . "', '" . $_POST['tyre'] . "', '" . $_POST['car']
    . "', '". $_POST['name'] . "', '" . $_POST['time'] . "', " . time() . ")";
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
        <form method="post" action="./index.php">
          <table>
            <tr>
              <td>Race:</td>
              <td>
                <select name="track">
                  <?
                  $races = F1Helper::getRaces();
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
                  $type = F1Helper::getTypes();
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
                  $tyre = F1Helper::getTyres();
                  for ($i=0; $i < sizeof($tyre); $i++) {
                    echo "<option value=\"" . $i . "\">". $tyre[$i] . "</option>\n";
                  }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Car:</td>
              <td>
                <select name="car">
                  <?
                  $cars = F1Helper::getCars();
                  for ($i=0; $i < sizeof($cars); $i++) {
                    echo "<option value=\"" . $i . "\">". $cars[$i] . "</option>\n";
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
              <th>Car</th>
              <th>Time</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM f1_track_times ORDER BY track ASC, time ASC, car ASC";
            $result = $db->sql_query($sql);
            while($row = mysql_fetch_assoc($result)) {
              echo "<tr><td>" . $row['name'] . "</td><td>"
                . $races[$row['track']] . "</td><td>" . $cars[$row['car']]
                . "</td><td>" . $row['time']
                . "</td><td>" . F1Helper::strtodate($row['date'])
                . "</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </body>
</html>
