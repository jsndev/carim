<?php
// Build the array of items to be used
exec("convert -list list", $IMarray, $code);
// Start the loop to find and display the results
foreach ($IMarray as $value) {
echo "<br>system (\"convert -list $value\")";
echo "<pre>";
system("convert -list $value");
echo "</pre><hr>";
}
?>
