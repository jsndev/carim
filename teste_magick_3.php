<?php

/* Create a new imagick object and read in GIF */
$im = new Imagick("input.jpg");

/* Resize all frames */
foreach ($im as $frame) {
    /* 50x50 frames */
        $frame->thumbnailImage(50, 50);

	    /* Set the virtual canvas to correct size */
	        $frame->setImagePage(50, 50, 0, 0);
		}

		/* Notice writeImages instead of writeImage */
		$im->writeImages("example_input.jpg", false);
?>
