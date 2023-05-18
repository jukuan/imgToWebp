# imgToWebp

imgToWebp is a PHP library for converting images (JPG, PNG, WebP) to other formats (webp by default) and resizing them on the fly. 
It provides a modern alternative to the old TimThumb script file, allowing you to easily generate resized version of images in your PHP applications.

## Features

- Convert images to WebP, JPG, or PNG formats
- Resize images on the fly
- Efficient image processing using GD library
- Easy to use and integrate with existing PHP applications

## Requirements

- PHP 8.1 or higher
- GD extension of PHP

## Installation

You can imgToWebp by composer or  
simply clone or download the repository and include files in your PHP application:

## Usage

Check the test directory. There are few examples how to use ir. 
To use imgToWebp, simply create a new `imgToWebp` object and call the `convert()` method with the source and destination paths:

You can use the following PHP code

```php
try {
    $imagePath = (new ImgConvertorService())
        ->setDirs($resizedImgDir, __DIR__)
        ->prepareImagePath();
    (new Response())->doImageOutput($imagePath);
} catch (\Exception $e) {
    // handle an error
}
```

In the URL you can specify additional options, such as the width, the height, the extension of output file:
For example: `https://example.com/resizer/?img=static/uploads/guy.jpg&w=120`


## Security Considerations

Please note that imgToWebp is provided "as is" without any guarantee, and it might be a bit dangerous to allow a PHP script to have write access to the directory. Use caution when using imgToWebp in production environments and ensure that your application has proper security measures in place.

## License

imgToWebp is licensed under the MIT License. See `LICENSE` for more information.

## Contributing

Contributions to imgToWebp are welcome! If you find a bug or have a feature request, please open an issue or submit a pull request.

## Credits

imgToWebp was created by [Julian M.]. The script is inspired by TimThumb library.

## Contact

If you have any questions or feedback about imgToWebp, please contact us at [i[at]juljan.by].
