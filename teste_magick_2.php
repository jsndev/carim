<?php

try
{
        /*** the image file ***/
	        $image = 'input.jpg';

		        /*** a new imagick object ***/
			        $im = new Imagick();

				        /*** ping the image ***/
					        $im->pingImage($image);

						        /*** read the image into the object ***/
							        $im->readImage( $image );

								        /*** thumbnail the image ***/
									        $im->thumbnailImage( 100, null );

										        /*** Write the thumbnail to disk ***/
											        $im->writeImage( '/tmp/spork_thumbnail.jpg' );

												        /*** Free resources associated with the Imagick object ***/
													        $im->destroy();

														        echo 'Thumbnail Created';
															}
															catch(Exception $e)
															{
															        echo $e->getMessage();
																}

?>
