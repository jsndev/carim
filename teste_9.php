<?php
// Replace input.jpg with the name of a jpg image in the same folder as this code and run it.
exec("convert input.jpg -thumbnail 50x50 output.jpg");
?>
<img src="output.jpg">
