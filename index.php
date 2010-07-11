<?PHP require('data.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Weight Tracking</title>
  <style type="text/css">
   table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
   td, th { border: 1px solid black; padding: 5px; }
   div#stats { margin-left: 810px; margin-right: 5px; padding-top: 1px; }
   td { text-align: right; }
   th { font-weight: bold; color: #fff; background-color: #000; border: 1px solid #666; }
   tr.vbad td, td.bad { background-color: #f99; }
   tr.bad td { background-color: #fdd; }
   tr.ok td { background-color: #fff; }
   tr.good td { background-color: #dfd; }
   tr.vgood td, td.good { background-color: #9f9; }
  </style>
 </head>
 <body>
  <div id="graph" style="float: left;">
   <img src="newergraph.php" alt="Graph of weight">
  </div>
  <div id="stats">
   <table>
    <tr>
     <th rowspan="2">Wk</th><th rowspan="2">Mass</th><th rowspan="2">BMI</th>
     <th colspan="2">&Delta;Mass</th><th colspan="2">&Sigma;&Delta;Mass</th>
    </tr><tr>
     <th>kg</th><th>st</th><th>kg</th><th>st</th>
    </tr>
<?PHP

 $last = 0;
 $first = 0;

 $number = 18;
 for ($i = count($data) - 1; $i > count($data) - $number; $i--) {
  if (isset($skips[$i])) { $number++; }
 }

 foreach ($data as $week => $weight) {
  $week++;
  if ($first == 0) {
   $first = $weight;
  }

  if ($last != 0) { $diffs[] = $weight - $last; }
 
  if ($week > count($data) - $number) {
  $diff = $weight - $last;

  if ($diff > 0.5) {
   $class = 'vbad';
  } else if ($diff > 0) {
   $class = 'bad';
  } else if ($diff == 0) {
   $class = 'ok';
  } else if ($diff > -1) {
   $class = 'good';
  } else {
   $class = 'vgood';
  }

  if (!isset($skips[$week - 1])) {
  echo '<tr class="', $class, '"><td>', $week, '</td><td>', sprintf('%01.1f', $weight), '</td><td>';
  echo sprintf('%01.1f', $weight / (HEIGHT * HEIGHT), 1), '</td><td>';

  if ($last == 0) {
   echo '-';
  } else {
   if ($weight - $last > 0) {
    echo '<span style="color: red;">';
   }
   echo sprintf('%01.1f', $weight - $last);
  }

  echo '</td><td>';

  if ($last == 0) {
   echo '-';
  } else {
   if ($weight - $last > 0) {
    echo '<span style="color: red;">';
   }
   echo sprintf('%01.1f', 0.157473044 * ($weight - $last));
  }

  echo '</td><td>', sprintf('%01.1f', $weight - $first), '</td>';
  echo '<td>', sprintf('%01.1f', 0.157473044 * ($weight - $first)), '</td></tr>';
  }
 }
  $last = $weight; 
 }

 $a1 = array_sum($diffs) / count($diffs);
 $a2 = array_sum(array_slice($diffs, -4)) / 4;
 $a3 = array_pop($diffs);

 $bmi = $last / (HEIGHT * HEIGHT);
 $target = 5 * floor($bmi/5);
 $target2 = $target - 5;
 $targs = array($target, $target2);

 $tweeks = array();
 foreach ($targets as $targ) {
  list($tweek, $tbmi) = $targ;
  if ($bmi > $tbmi && $tweek > $week) {
   $tweeks[$tbmi] = $tweek;
   $targs[] = $tbmi;
  }
 }
 rsort($targs);

 $target = array_shift($targs);
 $target2 = array_shift($targs);
 $b35 = $target * HEIGHT * HEIGHT - $last;
 $b30 = ($target2) * HEIGHT * HEIGHT - $last;

 $target_s = $target . ' (' . round($target * HEIGHT * HEIGHT, 1) . ')';
 $target2_s = $target2 . ' (' . round($target2 * HEIGHT * HEIGHT, 1) . ')';

?>
   </table>
   <table>
    <tr><th rowspan="2">Timespan</th><th rowspan="2"><span style="border-top: 1px solid white;">&Delta;Mass</span></th><th colspan="2">ETA &rarr; BMI (KG)</th></tr>
    <tr><th><?PHP echo $target_s; ?></th><th><?PHP echo $target2_s; ?></th></tr>
    <tr><td>All time</td><td><?PHP printf('%01.1f', $a1); ?></td>
     <td class="<?PHP if (isset($tweeks[$target])) { echo ($a1 < 0 && ceil(abs($b35 / $a1)) < $tweeks[$target] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a1 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b35 / $a1)).'weeks')); ?></td>
     <td class="<?PHP if (isset($tweeks[$target2])) { echo ($a1 < 0 && ceil(abs($b30 / $a1)) < $tweeks[$target2] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a1 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b30 / $a1)).'weeks')); ?></td>
    </tr>
    <tr><td>Last month</td><td><?PHP printf('%01.1f', $a2); ?></td>
     <td class="<?PHP if (isset($tweeks[$target])) { echo ($a2 < 0 && ceil(abs($b35 / $a2)) < $tweeks[$target] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a2 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b35 / $a2)).'weeks')); ?></td>
     <td class="<?PHP if (isset($tweeks[$target2])) { echo ($a2 < 0 && ceil(abs($b30 / $a2)) < $tweeks[$target2] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a2 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b30 / $a2)).'weeks')); ?></td>
    </tr>
    <tr><td>Last week</td><td><?PHP printf('%01.1f', $a3); ?></td>
     <td class="<?PHP if (isset($tweeks[$target])) { echo ($a3 < 0 && ceil(abs($b35 / $a3)) < $tweeks[$target] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a3 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b35 / $a3)).'weeks')); ?></td>
     <td class="<?PHP if (isset($tweeks[$target2])) { echo ($a3 < 0 && ceil(abs($b30 / $a3)) < $tweeks[$target2] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a3 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b30 / $a3)).'weeks')); ?></td>
    </tr>
    <tr><th>Average</th><td><?PHP printf('%01.1f', ($a1 + $a2 + $a3)/3); ?></td>
     <td class="<?PHP if (isset($tweeks[$target])) { echo ($a1 + $a2 + $a3 < 0 && ceil(abs($b35 / (($a1 + $a2 + $a3)/3))) < $tweeks[$target] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a1 + $a2 + $a3 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b35 / (($a1 + $a2 + $a3)/3))).'weeks')); ?></td>
     <td class="<?PHP if (isset($tweeks[$target2])) { echo ($a1 + $a2 + $a3 < 0 && ceil(abs($b30 / (($a1 + $a2 + $a3)/3))) < $tweeks[$target2] - count($data)) ? 'good' : 'bad'; } ?>">
      <?PHP echo $a1 + $a2 + $a3 >= 0 ? 'Never' : date('d M y', strtotime('+'.ceil(abs($b30 / (($a1 + $a2 + $a3)/3))).'weeks')); ?></td>
    </tr>
   </table>
  </div>
 </body>
</html>
