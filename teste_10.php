<?php

echo "Using convert:<br>";
$array = array();
echo "<pre>";
// Replace input.jpg with the name of a jpg image in the same folder as this code and run it.
exec("convert input.jpg -thumbnail 50x50 output1.jpg 2>&1", $array);
print_r( $array );
echo"</pre>";

echo "Using /usr/local/bin/convert:<br>";
$array = array();
echo "<pre>";
// Replace input.jpg with the name of a jpg image in the same folder as this code and run it.
exec("/usr/local/bin/convert input.jpg -thumbnail 50x50 output2.jpg 2>&1", $array);
print_r( $array );
echo"</pre>";

?>
<img src="output1.jpg"><br>
<p>
<img src="output2.jpg">
