<?php
header('Content-type: application/xml');
if (file_exists(__DIR__.'/seoshield-client/main.php')){
    include_once(__DIR__.'/seoshield-client/main.php');
    if(function_exists('seo_shield_start_cms')){
        seo_shield_start_cms();
    }
    $query = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("SELECT image_url, page_url FROM `ss_images_for_sitemap`");
    while($row = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_fetch_array($query)){
        $pages[$row['page_url']][] = $row['image_url'];
    }

    $output = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
    foreach ($pages as $page_url => $images) {
        $output .= "\n\t".'<url>'."\n\t\t".'<loc>'.$page_url.'</loc>';
        foreach ($images as $image_url) {
            $output .= "\n\t\t<image:image>";
            $output .= "\n\t\t<image:loc>$image_url</image:loc>";
            $output .= "\n\t\t</image:image>";
        }
        $output .= "\n\t".'</url>';

    }
    $output .= "\n".'</urlset>';
    echo $output;
    exit();
}

?>
