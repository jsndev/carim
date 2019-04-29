<?php
/* Create a new Imagick object */
$im = new Imagick();
/* Create empty canvas */
$im->newImage( 200, 200, "white", "png" );
/* Create the object used to draw */
$draw = new ImagickDraw();
/* Set the button color.
   Changing this value changes the color of the button */
   $draw->setFillColor( "#4096EE" );
   /* Create the outer circle */
   $draw->circle( 50, 50, 70, 70 );
   /* Create the smaller circle on the button */
   $draw->setFillColor( "white" );
   /* Semi-opaque fill */
   $draw->setFillAlpha( 0.2 );
   /* Draw the circle */
   $draw->circle( 50, 50, 68, 68 );
   /* Set the font */
   $draw->setFont( "./test1.ttf" );
   /* This is the alpha value used to annotate */
   $draw->setFillAlpha( 0.17 );
   /* Draw a curve on the button with 17% opaque fill */
   $draw->bezier( array(
			array( "x" => 10 , "y" => 25 ),
			array( "x" => 39, "y" => 49 ),
			array( "x" => 60, "y" => 55 ),
			array( "x" => 75, "y" => 70 ),
			array( "x" => 100, "y" => 70 ),
			array( "x" => 100, "y" => 10 ),
		) );

/* Render all pending operations on the image */
$im->drawImage( $draw );

/* Set fill to fully opaque */
$draw->setFillAlpha( 1 );

/* Set the font size to 30 */
$draw->setFontSize( 30 );

/* The text on the */
$draw->setFillColor( "white" );

/* Annotate the text */
$im->annotateImage( $draw, 38, 55, 0, "go" );

/* Trim extra area out of the image */
$im->trimImage( 0 );

/* Output the image */
header( "Content-Type: image/png" );
echo $im;
?>
