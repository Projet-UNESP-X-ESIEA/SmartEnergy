<?php

$stamp = "01-01-16 19:00:00";


$pattern = "/-\d{2}\s{1}/";
//$replacement = '-20${0}';

$matches = [];

preg_match($pattern, $stamp, $matches);

//echo $matches[0];

$replacement = "-".strval(intval(str_replace("-", "", $matches[0]))+2000)." ";

echo $stamp2 = preg_replace($pattern, $replacement,$stamp);
echo "<br>".strlen($stamp2);

?>