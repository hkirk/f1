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
  `time` INT NOT NULL ,
  `date` INT( 10 ) NOT NULL ,
  `year` INT( 5 ) NOT NULL
  ) ENGINE = MYISAM
 */
$year = 2011;
$sql = "";
$message = "";

$validateTime = false;

if (isset($_POST['time'])) {
  $time = F1Helper::parseTimeString($_POST['time']);
  if ($time == -1) {
    $message = "Time format m:ss.tht";
  } else {
    $validateTime = true;
  }
}

if ($validateTime && isset($_POST['name'])) {
  $error = false;
  $sql = "INSERT INTO f1_track_times VALUES ('', '" . $_POST['track']
          . "', '" . $_POST['type'] . "', '" . $_POST['tyre'] . "', '" . $_POST['car']
          . "', '" . $_POST['name'] . "', '" . $time . "', " . time() . ", '" . $year . "'"
          . ")";
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
echo "<b>" . $message . "</b><br/>";

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
<?php
$races = F1Helper::getRaces($year);
for ($i = 0; $i < sizeof($races); $i++) {
  echo "<option value=\"" . $i . "\">" . $races[$i] . "</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Type:</td>
            <td>
              <select name="type">
<?php
$type = F1Helper::getTypes();
for ($i = 0; $i < sizeof($type); $i++) {
  echo "<option value=\"" . $i . "\"";
  if ($i == F1Helper::getStandardType()) {
    echo " selected=\"selected\"";
  }
  echo ">" . $type[$i] . "</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Tyre:</td>
            <td>
              <select name="tyre">
<?php
$tyre = F1Helper::getTyres();
for ($i = 0; $i < sizeof($tyre); $i++) {
  echo "<option value=\"" . $i . "\">" . $tyre[$i] . "</option>\n";
}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Car:</td>
            <td>
              <select name="car">
<?php
$cars = F1Helper::getCars($year);
for ($i = 0; $i < sizeof($cars); $i++) {
  echo "<option value=\"" . $i . "\"";
  if ($i == F1Helper::getStandardCar()) {
    echo " selected=\"selected\"";
  }
  echo ">" . $cars[$i] . "</option>\n";
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
$sql = "SELECT * FROM f1_track_times "
     . "WHERE (track, name, year, time) IN ("
     . "  SELECT track, name, year, MIN(time)"
     . "  FROM f1_track_times"
     . "  GROUP BY track, name, year) "
     . " AND year = " . $year . " "
     . "ORDER BY track ASC, time ASC, car ASC";
$result = $db->sql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
  echo "<tr><td>" . $row['name'] . "</td><td>"
  . $races[$row['track']] . "</td><td>" . $cars[$row['car']]
  . "</td><td>" . F1Helper::getTimeString($row['time'])
  . "</td><td>" . F1Helper::strtodate($row['date'])
  . "</td></tr>";
}
?>
        </tbody>
      </table>
    </div>
  </body>
</html>
