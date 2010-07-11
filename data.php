<?PHP

 require('user-data.php');

 /* user-data.php contains user-supplied data.
  *
  * It should define a HEIGHT constant in metres, e.g.:
  *
  *   define('HEIGHT', 1.765);
  *
  * Weigh-ins are collected in a $data array, with one entry in kilograms per
  * week, e.g.:
  *
  *   $data = array(100.0, 99.0, 98.0);
  *
  * If a weight is recorded as -1, the week will be skipped and an average of
  * the nearest actual readings will be used. You can skip multiple weeks in
  * a row.
  *
  * Targets (shown as crosses on the graph) can be defined in an array called
  * $targets. Each entry in the array must be an array with two elements -
  * the week number and the target BMI (not weight!). e.g.:
  *
  *   $targets = array(array(52, 70.0));
  */

 $skips = array();

 // Replace -1 entries
 for ($i = 0; $i < count($data); $i++) {
  if ($data[$i] == -1) {
   $skips[$i] = true;

   $j = 1;
   while (isset($data[$i + $j]) && $data[$i + $j] == -1) { $j++; }
   $k = -1;
   while (isset($data[$i + $k]) && $data[$i + $k] == -1) { $k--; }

   if (isset($data[$i + $j])) {
    $data[$i] = round($data[$i + $k] + ($data[$i + $j] - $data[$i + $k]) / ($j - $k), 1);
   } else {
    $data[$i] = $data[$i - 1];
   }
  }
 }

?>
