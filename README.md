# php-gif
Below GIF image is an example image which can contain real-time data. A PHP script calculates the pending time till new year and generates the GIF image. An ideal solution for sending e-mails with real-time data to customers. E-mail clients give you the opportunity to load images by URL and so everytime the client re-opens the e-mail message, the GIF will be re-generated with real-time data.

For below image this doesn't work, unfortunately, cause github downloads the image and stores it. Most e-mail clients, however, do load the images directly from source :)

![Live countdown to new year](http://only-media.nl/gif/gif.php)

*Note: Gmail loads the images via their proxy, so not directly from source. But this shouldn't be any problem! There are different opinions about the proxy, but it seems that Google's proxy protects your private data and only informs the sender that the email has been opened. There are speculations that Gmail will cache the images via its proxy, but that cache respects the cache headers, so you can instruct Gmail how often to refresh the data.

I personally had no trouble with gmail whatsoever! This countdown image worked perfectly for me. You can test it yourself. Every single time, after reloading the e-mail message which contained the image it contained new data. So the didn't start all over again, but resumed where it had left off.*

##Getting Started

Create a file and add these headers at the beginning of the file:
```php
// Caching disable headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Output as a GIF image
header ('Content-type:image/gif');

// Include the GIFGenerator class
include('GIFGenerator.class.php');
```
On the next lines you can create a GIF image by first initializing the GIFGenerator object and creating an array with all the image frames:

```php
// Initialize a new GIFGenerator object
$gif = new GIFGenerator();

// Create a multidimensional array with all the image frames
$imageFrames = array(
	'repeat' => false,
	'frames' => array(
		array(
			'image' => './images/newyear.jpg',
			'text' => array(
				array(
					'text' => 'Hello GIF frame 1',
					'font-color' => '#000',
					'x-position' => 140,
					'y-position' => 138
				)
			),
			'delay' => 100
		),
	)
);
```
Finally you generate the image and `echo` the results on the screen: 
```php
echo $gif->generate($imageFrames);
```

##Example

A more complete example. You could copy/paste below code to a file and execute it in the browser to view a more complete result. As you can see it's not required to use text in your GIF image and you can add as much text per frame, and as much frames per GIF image as you like.

```php
<?php
// Caching disable headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Output as a GIF image
header ('Content-type:image/gif');

// Include the GIFGenerator class
include('GIFGenerator.class.php');

// Initialize a new GIFGenerator object
$gif = new GIFGenerator();

// Create a multidimensional array with all the image frames
$imageFrames = array(
	'repeat' => 5,
	'frames' => array(
		array(
			'image' => './images/newyear.jpg',
			'text' => array(
				array(
					'text' => 'Hello GIF frame 1',
					'font' => './fonts/Lato-Light.ttf',
					'font-size' => 30,
					'angle' => 0,
					'font-color' => '#000',
					'x-position' => 140,
					'y-position' => 138
				)
			),
			'delay' => 100
		),
		array(
			'image' => './images/newyear.jpg',
			'text' => array(
				array(
					'text' => 'Hello GIF frame 2',
					'font' => './fonts/Lato-Light.ttf',
					'font-size' => 15,
					'angle' => 0,
					'font-color' => '#000',
					'x-position' => 140,
					'y-position' => 138
				),
				array(
					'text' => 'Hello GIF frame 2',
					'font' => './fonts/Lato-Light.ttf',
					'font-size' => 15,
					'angle' => 0,
					'font-color' => '#000',
					'x-position' => 140,
					'y-position' => 108
				)
			),
			'delay' => 100
		),
		array(
			'image' => './images/newyear.jpg',
			'delay' => 50
		)
	)
);

echo $gif->generate($imageFrames);
?>
```

##License & Credits

This software is published under the [MIT License](https://en.wikipedia.org/wiki/MIT_License).

######GIFEncoder

GIFEncoder.class.php contains minor adaptations from the GIFEncoder PHP class by [László Zsidi](http://gifs.hu).
