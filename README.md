# Screenshot Gallery

This is a PHP-based screenshot gallery that displays images from the `screenshots` directory. The images are sorted by file modification time in descending order.

## Features

- Pagination: The gallery displays 12 images per page by default. This can be adjusted in the `$options` array.
- Lightbox: Clicking on an image opens it in a lightbox for a closer look.
- Metadata: Each image displays metadata including the map name, player name, GUID, and the date and time the screenshot was taken.

## Setup

1. Clone this repository or download the PHP file.
2. Place your screenshots in the `screenshots` directory. The screenshots should be in `.jpg` format.
3. Open the PHP file in a web browser to view the gallery.

## Dependencies

This project uses the following external libraries:

- Roboto Font
- Lightbox2

## Customization

You can customize the gallery by modifying the `$options` array at the top of the PHP file:

```php
$options = array(
    'pagetitle' => 'Screenshot Gallery',  // The page title
    'quantity'  => 12,                    // The number of images per page
    'around'    => 2,                     // The number of pages to display in the pagination around the current page
);

