<?php
 
/*
	A simple example demonstrate thumbnail creation.
*/ 
 
/* Create the Imagick object */
$im = new Imagick_();
 
/* Read the image file */
$im->readImage( 'test.jpg' );
 
/* Thumbnail the image ( width 100, preserve dimensions ) */
$im->thumbnailImage( 100, null );
 
/* Write the thumbail to disk */
$im->writeImage( 'tmp/th_test.png' );
 
/* Free resources associated to the Imagick object */
$im->destroy();
 
echo '<img src="tmp/th_test.png" />'
?>