<?php

//load in spherical class
require_once 'spherical.class.php';

//construct new sperical (unit, precision)
$sphere = new spherical('imperial');
echo "\nconstruct class with imperial unit base, no precision\n";

//points, array(lat,lng)
$point_a = array(44.1099510192871,-87.83435058593750);
$point_b = array(43.0439776000000,-87.89915139999999);

$data = $sphere->haversine($point_a,$point_b);
echo "haversine:\t\t\t\t".$data."\n";

$data = $sphere->law_of_cosines($point_a,$point_b);
echo "spherical law of cosines:\t\t".$data."\n";

$data = $sphere->equirectangular($point_a,$point_b);
echo "equirectangular approximation:\t\t".$data."\n";


//change up unit base & precision after construct example
$sphere->set_unit('metric');
$sphere->set_precision(5);

echo "\nswitch to metric, add precision of 5 decimal places\n";

$data = $sphere->equirectangular($point_a,$point_b);
echo "equirectangular approximation:\t\t".$data."\n\n";


?>