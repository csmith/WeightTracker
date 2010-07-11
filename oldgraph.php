<?PHP

 function array_min($array) {
  $min = 9999999999;

  foreach ($array as $v) { $min = min($v, $min); }

  return $min;
 }

 require_once('data.php');

 define('GWIDTH', 800);
 define('GHEIGHT', 792);

 define('XMIN', 0);
 define('XMAX', count($data));

 define('YMIN', ~1 & (floor(array_min($data))) - 10);
 define('YMAX', 140);

 $im = imagecreate(GWIDTH, GHEIGHT);

 $black = imagecolorallocate($im, 0x00, 0x00, 0x00);
 $grey  = imagecolorallocate($im, 0xCC, 0xCC, 0xCC);
 $white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
 $red   = imagecolorallocate($im, 0xAA, 0x33, 0x33);

 imagefill($im, 0, 0, $white);

 //imagerectangle($im, 0, 0, GWIDTH - 1, GHEIGHT - 1, $black);
 //imagefilledrectangle($im, 0, 0, GWIDTH - 1, 60, $black);
 imagerectangle($im, 75, 75, GWIDTH - 75, GHEIGHT - 75, $black);

 function dashLine($im, $y, $colour) {
  $n = 3;
  for ($i = 79; $i < GWIDTH - 79; $i += $n) {
   imageline($im, $i, $y, $i, $y, $colour);
   $n = 1 + ceil((($i - 70) / 3) / 20);
  }
 }

 function dashLine2($im, $y, $colour) {
  $n = 3;
  for ($i = GWIDTH - 79; $i > 79; $i -= $n) {
   imageline($im, $i, $y, $i, $y, $colour);
   $n = 1 + ceil(((GWIDTH - ($i + 79)) / 3) / 20);
  }
 }

 function fillBMI($im, $min, $max, $colour, $label) {
  $omin = $min; $omax = $max;

  $min = min(YMAX, max(YMIN, $min * HEIGHT * HEIGHT));
  $max = max(YMIN, min(YMAX, $max * HEIGHT * HEIGHT));

  if ($min == $max) { return; }

  $y1 = GHEIGHT - 75 - ($min - YMIN) * ((GHEIGHT - 150) / (YMAX - YMIN));
  $y2 = GHEIGHT - 75 - ($max - YMIN) * ((GHEIGHT - 150) / (YMAX - YMIN));

  imagefilledrectangle($im, 76, $max == YMAX ? $y2 + 1 : $y2, GWIDTH - 76, $y1 - 1, $colour);
  imageline($im, 76, $y2, GWIDTH - 76, $y2, imagecolorallocate($im, 0x00, 0x00, 0x00));
  $bmi = $omax == 100 ? "$omin+" : "$omin-$omax";
  imagestring($im, 1, 79, $y2 + 3, "BMI $bmi: '$label'", $black);
 }

 $colours = array(
  imagecolorallocate($im, 0xFF, 0xFF, 0xFF),
  imagecolorallocate($im, 0xE0, 0xFF, 0xFF),
  imagecolorallocate($im, 0xAD, 0xD8, 0xE1),
  imagecolorallocate($im, 0x87, 0xCE, 0xFA), 
  imagecolorallocate($im, 0x81, 0xAD, 0xD2),
  imagecolorallocate($im, 0x60, 0x80, 0xDF),
  imagecolorallocate($im, 0x00, 0x00, 0xFF)
 );

 $bmis = array(
  18.5,
  25,
  30,
  35,
  40,
  100
 );

 fillBMI($im, 0, 18.5, $colours[0], "Underweight");
 fillBMI($im, 18.5, 25, $colours[1], "Normal");
 fillBMI($im, 25, 30, $colours[2], "Overweight");
 fillBMI($im, 30, 35, $colours[3], "Obese class I");
 fillBMI($im, 35, 40, $colours[4], "Obese class II");
 fillBMI($im, 40, 100, $colours[5], "Obese class III");

 for ($i = YMIN; $i < YMAX; $i += 2) {
  $y = GHEIGHT - 75 - ($i - YMIN) * ((GHEIGHT - 150) / (YMAX - YMIN));

  imageline($im, 73, $y, 76, $y, $black);
  imagestring($im, 1, 55, $y - 4, STR_PAD($i, 3, ' ', STR_PAD_LEFT), $black);

  if ($i % 2 == 0 && $i > YMIN) {
   for ($j = 0; $j < count($bmis); $j++) {
    if ($bmis[$j] > ($i / (HEIGHT * HEIGHT))) { break; }
   }

   dashLine($im, $y, $colours[$j+1]);
  }
 }

 for ($i = ceil(YMIN / (HEIGHT*HEIGHT)); $i < ceil(YMAX / (HEIGHT*HEIGHT)); $i++) {
  $y = GHEIGHT - 75 - ($i * HEIGHT * HEIGHT - YMIN) * ((GHEIGHT - 150) / (YMAX - YMIN));

  imageline($im, GWIDTH - 76, $y, GWIDTH - 73, $y, $black);
  imagestring($im, 1, GWIDTH - 70, $y - 4, $i, $black);

  if ($i % 5 != 0) {
   for ($j = 0; $j < count($bmis); $j++) {
    if ($bmis[$j] > $i) { break; }
   } 

   dashLine2($im, $y, $colours[$j+1]);
  }
 }

 $lx = $ly = 0;

 foreach ($data as $i => $weight) {
  $y = GHEIGHT - 75 - ($weight - YMIN) * ((GHEIGHT - 150) / (YMAX - YMIN));
  $x = 75 + ($i - XMIN) * ((GWIDTH - 150) / (XMAX - XMIN));

  if ($lx != 0) {
   imageline($im, $lx, $ly, $x, $y, $ly > $y ? $red : $black);
  }

  imageline($im, $x, GHEIGHT - 76, $x, GHEIGHT - 73, $black);
  imagestring($im, 1, $x - 2, GHEIGHT - 70, $i + 1, $black);
  #imageline($im, $x - 4, $y - 4, $x + 4, $y + 4, $white);
  #imageline($im, $x + 4, $y - 4, $x - 4, $y + 4, $white);

  if ($i % 5 == 4) {
   imagerectangle($im, $x - 3, $y - 3, $x + 3, $y + 3, $black);
  } else {
   imagerectangle($im, $x - 2, $y - 2, $x + 2, $y + 2, $black);
  }

  $lx = $x; $ly = $y;
 }

 imagestring($im, 3, GWIDTH/2 - 50, GHEIGHT - 35, 'Week number', $black);
 imagestringup($im, 3, 20, GHEIGHT/2 + 20, 'Mass (KG)', $black);
 imagestringup($im, 3, GWIDTH - 30, GHEIGHT/2 + 50, 'BMI (KG/m^2)', $black);
 imagestring($im, 5, GWIDTH/2 - 110, 20, 'Graph of mass against time', $black);

 header('Content-type: image/png');
 imagepng($im); 

?>
