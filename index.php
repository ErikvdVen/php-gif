<?php
header ('Content-type:image/gif');
include('GIFEncoder.class.php');

$now = new DateTime();
$future_date = new DateTime('2016-01-01 00:00:00');
$interval = $future_date->diff($now);


$d = $interval->format("%a");
$h = $interval->format("%h");
$m = $interval->format("%i");
$s = $interval->format("%s");

for($i=1;$i<30;$i++){
		// Open the first source image and add the text.
		$image = imagecreatefromjpeg('./images/newyear_count.jpg');
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$font_file = './fonts/Lato-Light.ttf'; // This is the path to your font file.
		if($d < 10){
			imagettftext($image, 24, 0, 101, 130, $text_color, $font_file, $d);
		}else{
			imagettftext($image, 24, 0, 91, 130, $text_color, $font_file, $d);
		}
		if($h < 10){
			imagettftext($image, 24, 0, 227, 130, $text_color, $font_file, $h);
		}else{
			imagettftext($image, 24, 0, 217, 130, $text_color, $font_file, $h);
		}
		if($m < 10){
			imagettftext($image, 24, 0, 352, 130, $text_color, $font_file, $m);
		}else{
			imagettftext($image, 24, 0, 342, 130, $text_color, $font_file, $m);	
		}
		if($s < 10){
			imagettftext($image, 24, 0, 479, 130, $text_color, $font_file, $s);
		}else{
			imagettftext($image, 24, 0, 469, 130, $text_color, $font_file, $s);
		}

		// Generate GIF from the $image
		// We want to put the binary GIF data into an array to be used later,
		//  so we use the output buffer.
		ob_start();
		imagegif($image);
		$frames[]=ob_get_contents();
		$framed[]=100; // Delay in the animation.
		ob_end_clean();

		// And again..

		$future_date = $future_date->modify("-1 second");
		$interval = $future_date->diff($now);
		$d = $interval->format("%a");
		$h = $interval->format("%h");
		$m = $interval->format("%i");
		$s = $interval->format("%s");
}


$text = $interval->format("it's almost new year");

// Open the first source image and add the text.
$image = imagecreatefromjpeg('./images/newyear.jpg');
$text_color = imagecolorallocate($image, 0, 0, 0);
$font_file = './fonts/Lato-Light.ttf'; // This is the path to your font file.
imagettftext($image, 30, 0, 120, 135, $text_color, $font_file, $text);

// Generate GIF from the $image
// We want to put the binary GIF data into an array to be used later,
//  so we use the output buffer.
ob_start();
imagegif($image);
$frames[]=ob_get_contents();
$framed[]=100; // Delay in the animation.
ob_end_clean();
// Generate the animated gif and output to screen.
$gif = new GIFEncoder($frames,$framed,false,2,0,0,0,0,'bin');
echo $gif->GetAnimation();

?>