<?php
if ( isset($_POST['button']) && isset($_POST['url'])) {
    $html = file_get_contents($_POST['url']);
    if ($html !== false) {
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $images = $dom->getElementsByTagName('img');
        $imgUrls = [];
        foreach ($images as $img) {
            $imgSrc = $img->getAttribute('src');         
            $imgUrls[] = $imgSrc;
        }
        $summarySize = 0;
        foreach ($imgUrls as $imgUrl) {
            $headers = get_headers($imgUrl, 1);
            $summarySize += (int)$headers['Content-Length'];
        }
        $summarySize = $summarySize/1024;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изображения с URL</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
        }
        img {
            width: 200px;
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
<form method="POST">
    <input type="url" name="url" required>
    <input type="submit" name="button" value="Go">
</form>
<?php
echo "<table>";
$count = 0;
    foreach ($imgUrls as $imgUrl) {
        if ($count % 4 == 0) {
            echo "<tr>";
        }
        $count++;
        echo "<td><img src=\"" . htmlspecialchars($imgUrl) . "\" style='width:200px'></td>";
        if ($count % 4 == 0) {
            echo "</tr>";
        }   
    }
    echo "</table>";
    echo "<h2>На странице обнаружено изображений: ".count($imgUrls)." на ". round($summarySize, 1) . " Кб";
    ?>
</body>
</html>
