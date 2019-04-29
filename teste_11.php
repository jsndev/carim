<?php
header('Content-type: image/jpeg');
#$image = new Imagick( 'input.jpg' );
$image = new Imagick( dirname(__FILE__) . '/test.jpg' );
$image->thumbnailImage(100, 0);
echo $image;
?>
