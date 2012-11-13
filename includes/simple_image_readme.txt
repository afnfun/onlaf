The Simple_Image PHP class - v1.0

By Cory LaViska - http://abeautifulsite.net/

Overview

	This class attempts to make basic image manipulation easier in PHP.  Each method is called 
	as a static function:

	  Simple_Image::[method-name]($arg1, $arg2, $arg3, ...)

	All methods require a $src file and a $dest file.  If you're not worried about preserving
	the original image, $src and $dest can be the same file.

	Many of the arguments are self explanatory.  The $quality parameter can always be omitted,
	but if you need to set it then use 0-9 for PNG compression and 0-100 for JPEG quality (it
	is ignored for GIF files).


Requirements

	This class requires the PHP GD library.  Some methods (i.e. colorize and pixelate) 
	require	a more recent version of PHP.  The rest can be used with any recent version 
	of PHP + GD.


License

	This software is dual-licensed under the GNU General Public License and the MIT License 
	and is copyright A Beautiful Site, LLC.


Usage

	Simple_Image::convert('image.jpg', 'image.gif');

	Simple_Image::flip('image.jpg', 'image-flipped.jpg', 'x');

	Simple_Image::rotate('image.jpg', 'image-rotated.jpg', 180);

	Simple_Image::grayscale('image.jpg', 'image-greyscale.jpg');

	Simple_Image::invert('image.jpg', 'image-invert.jpg');

	Simple_Image::brightness('image.jpg', 'image-brightness.jpg', 50);

	Simple_Image::contrast('image.jpg', 'image-contrast.jpg', 50);

	Simple_Image::colorize('image.jpg', 'image-colorize.jpg', 255, 150, 0, 100);

	Simple_Image::edgedetect('image.jpg', 'image-edgedetect.jpg');

	Simple_Image::emboss('image.jpg', 'image-emboss.jpg');

	Simple_Image::blur('image.jpg', 'image-blur.jpg', 4);

	Simple_Image::smooth('image.jpg', 'image-smooth.jpg', 100);

	Simple_Image::pixelate('image.jpg', 'image-pixelate.jpg', 4);

	Simple_Image::sepia('image.jpg', 'image-sepia.jpg');

	Simple_Image::resize('image.jpg', 'image-resize.jpg', 50, 200);

	Simple_Image::resize_to_width('image.jpg', 'image-resize-to-width.jpg', 2000);

	Simple_Image::resize_to_height('image.jpg', 'image-resize-to-height.jpg', 100);

	Simple_Image::shrink_to_fit('image.jpg', 'image-shrink.jpg', 100, 100);

	Simple_Image::crop('image.jpg', 'image-crop.jpg', 100, 100, 500, 400);

	Simple_Image::square_crop('image.jpg', 'image-square_crop.jpg', 128);

	Simple_Image::watermark('image.jpg', 'image-watermark.jpg', 'watermark.png', 'bottom-right', 50, 10);

	Simple_Image::text('image.jpg', 'image-text.jpg', 'Sample Text', 'Delicious-Bold.ttf', 36, '#FFF', 'bottom-center', 10, '#000', -2, 2);

