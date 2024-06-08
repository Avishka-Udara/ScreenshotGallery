<?php
$options = array(
    'pagetitle' => 'Screenshot Gallery',
    'quantity'  => 12,
    'around'    => 2,
);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $options['quantity'];
$filelist = glob('screenshots/*.jpg');
usort($filelist, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$totalPages = ceil(count($filelist) / $options['quantity']);
$selectedFiles = array_slice($filelist, $offset, $options['quantity']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($options['pagetitle']); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
            margin-top: 50px;
            color: #333;
        }
        .gallery-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 30px;
        }
        .gallery-item {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            transition: transform .2s; /* Animation */
        }
        .gallery-item:hover {
            transform: scale(1.05); /* (105% zoom) */
        }
        .gallery-item img {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 100%;
            height: auto;
            display: block;
        }
        .gallery-item-caption {
            padding-top: 5px;
            padding-bottom: 5px;
            color: #333;
            font-size: 14px;
            text-align: center;
        }

        .gallery-item-footer {
            padding-top: 5px;
            padding-bottom: 5px;
            color: #a5a5a5;
            font-size: 12px;
            text-align: center;
        }
        .pagination {
            text-align: center;
            margin-top: 50px;
        }
        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin: 0 5px;
            transition: background-color .2s; /* Animation */
        }
        .pagination a.active,
        .pagination a:hover {
            background-color: #0056b3;
        }
        .pagination a.disabled {
            pointer-events: none;
            background-color: #ccc;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.css" rel="stylesheet" />
</head>
<body>

<body>
    <header style="background-color: #565656; padding: 20px; color: white; text-align: center;">
        <h1 style="font-size: 2.5em; font-family: Arial, sans-serif;">HEY MF THIS IS GALLARY</h1>
        <p style="font-size: 14px; font-family: Arial, sans-serif;">
             this is a gallery of screenshots taken from the game server and this shows the meta data of the screenshot like map name, player name, GUID, shot date and time.
        </p>
    </header>

    <div class="gallery-container">
        <?php foreach ($selectedFiles as $file) : ?>
            <?php if (is_array(getimagesize($file))) : ?>
                <?php
                $filedata = file_get_contents($file);
                $metadata = strpos($filedata, "CoD4X");
                $metastring = substr($filedata, $metadata);
                $exifdata = explode("\0", $metastring);
                $map = $exifdata[2];
                $playername = strip_tags($exifdata[3]);
                $guid = $exifdata[4];
                $time = $exifdata[6];
                $ssdate = new DateTime($time);
                $shortdate = $ssdate->format('Y-m-d');
                $shorttime = $ssdate->format('g:i:s A');
                $caption = ' shot date: ' . htmlspecialchars($shortdate) . ' <br> ' . ' shot time : ' . $shorttime . ' <br> ' . ' map : '  . htmlspecialchars($map) . ' <br> ' . ' GUID : '  . htmlspecialchars($guid);
                ?>
                <div class="gallery-item">
                    <a href="<?php echo $file; ?>" data-lightbox="gallery" data-title="<?php echo $caption; ?>">
                        <img src="<?php echo $file; ?>" alt="<?php echo htmlspecialchars(basename($file)); ?>">
                    </a>
                    <div class="gallery-item-caption">
                        <?php echo htmlspecialchars($playername); ?>
                    </div>
                    <div class="gallery-item-footer">
                        <?php echo $caption; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <a <?php echo $page <= 1 ? 'class="disabled"' : ''; ?> href="?page=<?php echo $page - 1; ?>"><</a>
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <?php if (($i == 1 || $i == $totalPages) || ($i >= $page - $options['around'] && $i <= $page + $options['around'])) : ?>
                <a <?php echo $page == $i ? 'class="active"' : ''; ?> href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php elseif ($i == 2 || $i == $totalPages - 1) : ?>
                <span>…</span>
            <?php endif; ?>
        <?php endfor; ?>
        <a <?php echo $page >= $totalPages ? 'class="disabled"' : ''; ?> href="?page=<?php echo $page + 1; ?>">></a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox-plus-jquery.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
          lightbox.option({
              'resizeDuration': 200,
              'wrapAround': true
          });
      });
    </script>
</body>
</html>
