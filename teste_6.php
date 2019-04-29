<?php
exec("/usr/bin/convert -version",$out,$returnval);
print_r($out[0]);
?>
