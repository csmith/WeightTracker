<?PHP

 require('user-data.php');

 /* For format of user-data.php see comment in data.php.
  *
  * In addition, this script requires a START_DATE constant containing
  * the unix timestamp of the first entry in the $data array.
  */

 foreach ($data as $week => $value) {
  if ($value != -1) {
   echo date('Y-m-d', strtotime('+' . $week . ' weeks', START_DATE)), ';', $value, "\n";
  }
 }

?>
