<?PHP


 function imagesmoothline ( $image , $x1 , $y1 , $x2 , $y2 , $color )
 {
  $colors = imagecolorsforindex ( $image , $color );
  if ( $x1 == $x2 )
  {
   imageline ( $image , $x1 , $y1 , $x2 , $y2 , $color ); // Vertical line
  }
  else
  {
   $m = ( $y2 - $y1 ) / ( $x2 - $x1 );
   $b = $y1 - $m * $x1;
   if ( abs ( $m ) <= 1 )
   {
    $x = min ( $x1 , $x2 );
    $endx = max ( $x1 , $x2 );
    while ( $x <= $endx )
    {
     $y = $m * $x + $b;
     $y == floor ( $y ) ? $ya = 1 : $ya = $y - floor ( $y );
     $yb = ceil ( $y ) - $y;
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , floor ( $y ) ) );
     $tempcolors['red'] = $tempcolors['red'] * $ya + $colors['red'] * $yb;
     $tempcolors['green'] = $tempcolors['green'] * $ya + $colors['green'] * $yb;
     $tempcolors['blue'] = $tempcolors['blue'] * $ya + $colors['blue'] * $yb;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , $x , floor ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , ceil ( $y ) ) );
     $tempcolors['red'] = $tempcolors['red'] * $yb + $colors['red'] * $ya;
      $tempcolors['green'] = $tempcolors['green'] * $yb + $colors['green'] * $ya;
     $tempcolors['blue'] = $tempcolors['blue'] * $yb + $colors['blue'] * $ya;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , $x , ceil ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $x ++;
    }
   }
   else
   {
    $y = min ( $y1 , $y2 );
    $endy = max ( $y1 , $y2 );
    while ( $y <= $endy )
    {
     $x = ( $y - $b ) / $m;
     $x == floor ( $x ) ? $xa = 1 : $xa = $x - floor ( $x );
     $xb = ceil ( $x ) - $x;
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , floor ( $x ) , $y ) );
     $tempcolors['red'] = $tempcolors['red'] * $xa + $colors['red'] * $xb;
     $tempcolors['green'] = $tempcolors['green'] * $xa + $colors['green'] * $xb;
     $tempcolors['blue'] = $tempcolors['blue'] * $xa + $colors['blue'] * $xb;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , floor ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , ceil ( $x ) , $y ) );
     $tempcolors['red'] = $tempcolors['red'] * $xb + $colors['red'] * $xa;
     $tempcolors['green'] = $tempcolors['green'] * $xb + $colors['green'] * $xa;
     $tempcolors['blue'] = $tempcolors['blue'] * $xb + $colors['blue'] * $xa;
     if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
     imagesetpixel ( $image , ceil ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
     $y ++;
    }
   }
  }
 }

 function array_min($array) {
  $min = 9999999999;

  foreach ($array as $v) { $min = min($v, $min); }

  return $min;
 }

 require_once('data.php');

 define('GWIDTH', 800);
 define('GHEIGHT', 792);

 $maxtweek = 0; $mintbmi = 100;
 foreach ($targets as $target) {
  list($week, $bmi) = $target;
  $maxtweek = max($week - 1, $maxtweek);
  $mintbmi = min($mintbmi, $bmi);
 }
 $mintmass = $mintbmi * HEIGHT * HEIGHT;

 define('XMIN', 0);
 define('XMAX', max($maxtweek + 1, count($data)));

 define('YMIN', ~1 & min($mintmass, floor(array_min($data)) - 10));
 define('YMAX', 140);

 $im = imagecreate(GWIDTH, GHEIGHT);

 $black = imagecolorallocate($im, 0x00, 0x00, 0x00);
 $grey  = imagecolorallocate($im, 0x66, 0x66, 0x66);
 $white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
 $red   = imagecolorallocate($im, 0xAA, 0x33, 0x33);

 imagefill($im, 0, 0, $white);

 //imagerectangle($im, 0, 0, GWIDTH - 1, GHEIGHT - 1, $black);
 //imagefilledrectangle($im, 0, 0, GWIDTH - 1, 60, $black);
 imagerectangle($im, 65, 45, GWIDTH - 55, GHEIGHT - 45, $black);

 function dashLine($im, $y, $colour) {
  $n = 3;
  for ($i = 69; $i < GWIDTH - 59; $i += $n) {
   imageline($im, $i, $y, $i, $y, $colour);
   $n = 1 + ceil((($i - 60) / 3) / 20);
  }
 }

 function dashLine2($im, $y, $colour) {
  $n = 3;
  for ($i = GWIDTH - 59; $i > 69; $i -= $n) {
   imageline($im, $i, $y, $i, $y, $colour);
   $n = 1 + ceil(((GWIDTH - ($i + 59)) / 3) / 20);
  }
 }

 function fillBMI($im, $min, $max, $colour, $label) {
  $omin = $min; $omax = $max;

  $min = min(YMAX, max(YMIN, $min * HEIGHT * HEIGHT));
  $max = max(YMIN, min(YMAX, $max * HEIGHT * HEIGHT));

  if ($min == $max) { return; }

  $y1 = GHEIGHT - 45 - ($min - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));
  $y2 = GHEIGHT - 45 - ($max - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));

  imagefilledrectangle($im, 66, $max == YMAX ? $y2 + 1 : $y2, GWIDTH - 56, $y1 - 1, $colour);
  imageline($im, 66, $y2, GWIDTH - 56, $y2, imagecolorallocate($im, 0x00, 0x00, 0x00));
  $bmi = $omax == 100 ? "$omin+" : "$omin-$omax";
  imagestring($im, 1, 69, $y2 + 3, "BMI $bmi: '$label'", $black);
 }

 $colours = array(
  imagecolorallocate($im, 0xFF, 0xFF, 0xFF),
  imagecolorallocate($im, 0xE0, 0xFF, 0xFF),
  imagecolorallocate($im, 0xC0, 0xDE, 0xEC),
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
  $y = GHEIGHT - 45 - ($i - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));

  imageline($im, 63, $y, 66, $y, $black);
  imagestring($im, 1, 45, $y - 4, STR_PAD($i, 3, ' ', STR_PAD_LEFT), $black);

  if ($i % 2 == 0 && $i > YMIN) {
   for ($j = 0; $j < count($bmis); $j++) {
    if ($bmis[$j] > ($i / (HEIGHT * HEIGHT))) { break; }
   }

   dashLine($im, $y, $colours[$j+1]);
  }
 }

 for ($i = ceil(YMIN / (HEIGHT*HEIGHT)); $i < ceil(YMAX / (HEIGHT*HEIGHT)); $i++) {
  $y = GHEIGHT - 45 - ($i * HEIGHT * HEIGHT - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));

  imageline($im, GWIDTH - 56, $y, GWIDTH - 53, $y, $black);
  imagestring($im, 1, GWIDTH - 50, $y - 4, $i, $black);

  if ($i % 5 != 0) {
   for ($j = 0; $j < count($bmis); $j++) {
    if ($bmis[$j] > $i) { break; }
   } 

   dashLine2($im, $y, $colours[$j+1]);
  }
 }

 $lx = $ly = 0;
 $points = array();

 for($i = 0; $i < XMAX; $i++) {
  $x = 65 + ($i - XMIN) * ((GWIDTH - 120) / (XMAX - XMIN));

  if ($lx != 0) {
   //imageline($im, $lx, $ly, $x, $y, $ly > $y ? $red : $black);
  }

  imageline($im, $x, GHEIGHT - 46, $x, GHEIGHT - 43, $black);
  imagestring($im, 1, $x - 2, GHEIGHT - 40, substr($i + 1, 0, 1), $black);
  if ($i >= 9) {
   imagestring($im, 1, $x - 2, GHEIGHT - 32, substr($i + 1, 1, 1), $black);
  }
  #imageline($im, $x - 4, $y - 4, $x + 4, $y + 4, $white);
  #imageline($im, $x + 4, $y - 4, $x - 4, $y + 4, $white);

  if (isset($data[$i])) {
   $weight = $data[$i];
   $y = GHEIGHT - 45 - ($weight - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));
   $points[] = array($x, $y);

   if ($i % 5 == 4) {
    //dashLine3($im, $x, $y, imagecolorallocatealpha($im, 0x66, 0x66, 0x66, 10));// $i % 5 == 4 ? $black : $grey);
    //imagearc($im, $x, $y, 10, 10, 0, 360, $red);
   }// else {
    imagefilledarc($im, $x, $y, 3, 3, 0, 360, $red, IMG_ARC_PIE);
   //}

   $lx = $x; $ly = $y;
  }
 }

 foreach ($targets as $target) {
  list($week, $bmi) = $target;
  $weight = $bmi * HEIGHT * HEIGHT;

  if ($week > 0) {
   $x = 65 + (($week - 1) - XMIN) * ((GWIDTH - 120) / (XMAX - XMIN));
   $y = GHEIGHT - 45 - ($weight - YMIN) * ((GHEIGHT - 90) / (YMAX - YMIN));
   imageline($im, $x - 4, $y - 4, $x + 4, $y + 4, $red);
   imageline($im, $x - 4, $y + 4, $x + 4, $y - 4, $red);
  }
 }

 $avg = array();
 $avg[] = $points[0];
 for ($i = 0; $i < XMAX - XMIN - 5; $i += 5) {
  $tx = $ty = 0;
  for ($j = 0; $j < 5; $j++) {
   list($x, $y) = $points[$i + $j];
   $tx += $x; $ty += $y;
  }
  $avg[] = array($tx/5, $ty/5);
 }

 $colours = array($red, $black);
 foreach (array(/*$avg,*/ $points) as $points) {
  $colour = array_shift($colours);
  $lx = $ly = 0;
  for ($i = 0; $i < count($points) - 1; $i++) {
   $func = interpolate($points, $i, $i + 1);
   list($x1,$y1) = $points[$i];
   list($x2,$y2) = $points[$i + 1];
   for ($t = 0; $t < 1; $t += 4 / ($x2 - $x1)) {
    $x = round($x1 + ($x2 - $x1) * $t, 0);
    $y = round(call_user_func($func, $t), 0);
   
    if ($lx != 0) {
     imagesmoothline($im, $lx, $ly, $x, $y, $red); 
    }
    $lx = $x; $ly = $y;
   }
  }
 }

 function h00($t) { return 2 * pow($t, 3) - 3 * pow($t, 2) + 1; }
 function h10($t) { return pow($t, 3) - 2 * pow($t, 2) + $t; }
 function h01($t) { return -2 * pow($t, 3) + 3 * pow($t, 2); }
 function h11($t) { return pow($t, 3) - pow($t, 2); }
 function p($t, $p0, $m0, $p1, $m1, $h) { return h00($t) * $p0 + h10($t) * $h * $m0 + h01($t) * $p1 + h11($t) * $h * $m1; }

 function getLineGradient($a, $b) {
  list($x1,$y1) = $a;
  list($x2,$y2) = $b;
  $dx = $x2 - $x1;
  $dy = $y2 - $y1;
  if ($dx == 0) { return PHP_INT_MAX; }
  return $dy/$dx;
 }

 function getPointGradient($cur, $last, $next) {
  if ($last == null) { return getLineGradient($cur, $next); }
  if ($next == null) { return getLineGradient($last, $cur); }
  return (getLineGradient($cur, $next) + getLineGradient($last, $cur))/2;
 }

 function interpolate($data, $from, $to) {
  $prev = isset($data[$from - 1]) ? $data[$from - 1] : null;
  $next = isset($data[$to + 1]) ? $data[$to + 1] : null;

  $one = $data[$from];
  $two = $data[$to];

  list($x1, $y1) = $one;
  list($x2, $y2) = $two;

  $h = $x2 - $x1;
  $p0 = $y1; $p1 = $y2;
  $m0 = getPointGradient($prev, $one, $two);
  $m1 = getPointGradient($one, $two, $next);

  return create_function('$t', 'return p($t, ' . $p0 . ', ' . $m0 . ', ' . $p1 . ', ' . $m1 . ', ' . $h . ');');
 }

 imagestring($im, 3, GWIDTH/2 - 50, GHEIGHT - 20, 'Week number', $black);
 imagestringup($im, 3, 10, GHEIGHT/2 + 20, 'Mass (KG)', $black);
 imagestringup($im, 3, GWIDTH - 20, GHEIGHT/2 + 50, 'BMI (KG/m^2)', $black);
 imagestring($im, 5, GWIDTH/2 - 110, 10, 'Graph of mass against time', $black);

 header('Content-type: image/png');
 imagepng($im); 

?>
