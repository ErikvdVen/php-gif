<?php
include('GIFEncoder.class.php');

Class GIFGenerator {

	private $_defaultYPosition;
	private $_defaultXPosition;
	private $_defaultAngle;
	private $_defaultFont;
	private $_defaultFontColor;
	private $_defaultFontSize; 
	private $_defaultDelay; 
	private $_defaultRepeat; 

	function __construct(array $kwargs = array()) {

		// Set defaults
		$defaults = array(
	        "y-position" => 100,
	        "x-position" => 100,
	        "angle" => 0,
	        "fonts" => __DIR__.'/fonts/Lato-Light.ttf',
	        "fonts-color" => array(255,255,255),
	        "fonts-size" => 12,
	        "delay" => 100,
	        "repeat" => 0
	    );

		// Overwrite all the defaults with the arguments
    	$args = array_merge($defaults,$kwargs);
		
		$this->_defaultYPosition = $args['y-position'];
		$this->_defaultXPosition = $args['x-position'];
		$this->_defaultAngle = $args['angle'];
		$this->_defaultFont = $args['fonts'];
		$this->_defaultFontColor = $args['fonts-color'];
		$this->_defaultFontSize = $args['fonts-size'];
		$this->_defaultDelay = $args['delay'];
		$this->_defaultRepeat = $args['repeat'];
	}

    private function imagettftextSp($image, $size, $angle, $x, $y, $color, $font, $text, $spacing = 0)
    {
        if ($spacing == 0)
        {
            $bbox = imagettftext($image, $size, $angle, $x, $y, $color, $font, $text);
        }
        else
        {
            $temp_x = $x;
            for ($i = 0; $i < strlen($text); $i++)
            {
                $bbox = imagettftext($image, $size, $angle, $temp_x, $y, $color, $font, $text[$i]);
                $temp_x += $spacing + ($bbox[2] - $bbox[0]);
            }
        }
    }

	public function generate(array $array) {
		$frames = array();
		$frame_delay = array();

		foreach($array['frames'] as $frame) {

			$image = $this->_createImage($frame['image']);
			
			if(array_key_exists('text', $frame))
				foreach($frame['text'] as $text) {

                    // Set defaults
                    $defaults = array(
                        "angle" => $this->_defaultAngle,
                        "fonts" => $this->_defaultFont,
                        "fonts-color" => $this->_defaultFontColor,
                        "fonts-size" => $this->_defaultFontSize,
                        "y-position" => $this->_defaultYPosition,
                        "x-position" => $this->_defaultXPosition,
                        "text" => null,
                        "letter-spacing" => 0
                    );

                    // Overwrite all the defaults with the arguments
                    $args = array_merge($defaults, $text);
                    $fontColor = is_array($args['fonts-color']) ? $args['fonts-color'] : $this->_hex2rgb($args['fonts-color']);
                    $text_color = imagecolorallocate($image, $fontColor[0], $fontColor[1], $fontColor[2]);

                    $this->imagettftextSp(
                        $image,
                        $args['fonts-size'],
                        $args['angle'],
                        $args['x-position'],
                        $args['y-position'],
                        $text_color,
                        $args['fonts'],
                        $args['text'],
                        $args['letter-spacing']);
				}

			$delay = (array_key_exists('delay', $frame)) ? $frame['delay'] : $this->_defaultDelay;

			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$frame_delay[]=$delay; // Delay in the animation.
			ob_end_clean();
		}


		$repeat = (array_key_exists('repeat', $array)) ? $array['repeat'] : $this->_defaultRepeat;
		$gif = new GIFEncoder($frames,$frame_delay,$repeat,2,0,0,0,0,'bin');
		return $gif->GetAnimation();
	}

	private function _createImage($imagePath) {
		$cImage = null;
		$tmp = explode('.', $imagePath);
		$ext = end($tmp);

		switch(strtolower($ext)){
			case 'jpg':
			case 'jpeg':
				$cImage = imagecreatefromjpeg($imagePath);
				break;
			case 'png':
				$cImage = imagecreatefrompng($imagePath);
				break;
		}

		return $cImage;
	}

	private function _endsWith($haystack, $needle) {
	    // search forward starting from end minus needle length characters
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}

	private function _hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);

	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
}
?>