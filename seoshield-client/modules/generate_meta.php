<?php
/*
* модуль подменяет динамические мета данные
*/

if (!defined('SEOSHIELD_ROOT_PATH')) {
    define('SEOSHIELD_ROOT_PATH', rtrim(realpath(dirname(__FILE__)), '/'));
}

class SeoShieldModule_generate_meta_config extends seoShieldModule_generate_meta
{
    public function html_out_buffer($out_html)
    {
    	$out_html = str_replace('</head>', "\n".'<link href="/seoshield-client/shield.css?'.microtime(true).'" rel="stylesheet">'."\n".'</head>', $out_html);
    	$out_html = $this->make_filters_visible_for_robots($out_html);
        $out_html = $this->bigrammaUkr($out_html);
        $out_html = $this->bigrammaRus($out_html);
        $out_html = $this->ldJsonMarkup($out_html);
        $out_html = $this->imagesSitemap($out_html);

        if ((strpos($out_html, '<!--seoshield_formulas--sluzhebnye-->') !== false 
        || strpos($out_html, '<!--seoshield_formulas--novosti-->') !== false)
        && preg_match('#<meta[^>]*?name=[\'\"]?description[^>]*?>#s', $out_html)){
            $out_html = preg_replace('#<meta[^>]*?name=[\'\"]?description[^>]*?>#s', '', $out_html);
        }

        if (strpos($out_html, '<!--dg_crumb_url:') !== false && strpos($_SERVER['REQUEST_URI'], '/ru/') !== false){
            $out_html = str_replace('Главная', 'Эксперты в напольном покрытии MDTgroup', $out_html);
        }

        if(function_exists("http_response_code")) {
            $status = http_response_code();
            if(isset($status) && $status == '404'){
                $out_html = $this->replace_page_title($out_html, '404 - Страница не найдена');
            }
        }


        // $out_html=$this->replace_page_h1($out_html,$GLOBALS['SEOSHIELD_CONFIG']['page_h1']);
        // $out_html=$this->replace_page_meta_description($out_html,$GLOBALS['SEOSHIELD_CONFIG']['page_meta_description']);
        // $out_html=$this->replace_page_title($out_html,$GLOBALS['SEOSHIELD_CONFIG']['page_title']);
        // $out_html=$this->replace_page_meta_keywords($out_html,$GLOBALS['SEOSHIELD_CONFIG']['page_meta_keywords']);
        return $out_html;
    }



    function make_filters_visible_for_robots($out_html){
		if (preg_match('#<link[^>]*?rel=[\'\"]?canonical[\'\"\s]?[^>]*?>#s', $out_html)){
			$out_html = preg_replace('#<link[^>]*?rel=[\'\"]?canonical[\'\"\s]?[^>]*?>#s', '', $out_html);
		}

		// --- Если есть роботс с контентом "nofollow" в любом регистре или его нет вовсе --- // 
		if (preg_match('#<meta[^>]*?name=[\'\"]?robots[\'\"]?[^>]*?content=[\'\"]?[^>]*?noindex[^>]*?>#si', $out_html) || !preg_match('#<meta[^>]*?name=[\'\"]?robots[\'\"]?[^>]*?>#si', $out_html)){
			$out_html = preg_replace('#<meta[^>]*?name=[\'\"]?robots[\'\"]?[^>]*?>#s', '', $out_html);
			$out_html = str_replace('</head>', '<meta name="robots" content="index, follow" />'."\n".'</head>', $out_html);
		}

		return $out_html;
	}

    private function bigrammaUkr($out_html){
        $curr_uri = $_SERVER['REQUEST_URI'];
        if (strpos($curr_uri, "?") !== false){
            $curr_uri_no_query_string = preg_replace('#\?.+#s', '', $curr_uri);
        } else {
            $curr_uri_no_query_string = $curr_uri;
        }
        $md5_uri = md5($curr_uri_no_query_string);

        if (strpos($out_html, '<!--seoshield_formulas--kartochka-->') !== false || strpos($out_html, '<!--seoshield_formulas--kategoriya-->') !== false){

            $create_bigramma_table = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query('CREATE TABLE IF NOT EXISTS `ss_bigram_ukr_pages`(
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `url_hash` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `bigramma` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`url_hash`)
            )');    

            if (!$create_bigramma_table){
                $out_html = str_replace('</body>', '<!--SEOSHIELD_INFO:SQL_ERROR:'.var_export($GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_error(), true).'--></body>', $out_html);
            } else {
                $result_db = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("SELECT bigramma FROM `ss_bigram_ukr_pages` WHERE url_hash = '".$md5_uri."'");
                $bigramma_1 = array('ковролін',
                                    'пвх плитку',
                                    'гомогенний лінолеум',
                                    'гомогенний вініл',
                                    'спортивний лінолеум',
                                    'лінолеум');
                if ($result_db !== false && $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_num_rows($result_db) != 0){
                    while($res = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_fetch_array($result_db)){
                        $db_bigramma_1[] = $res['bigramma'];
                    }
                    $out_html = str_replace('{bigramma_1}', $db_bigramma_1[0], $out_html);
                } else {
                    shuffle($bigramma_1);
                    $single_bigramma_1 = array_pop($bigramma_1);
                    $out_html = str_replace('{bigramma_1}', $single_bigramma_1, $out_html);
                    $add_url_hash_query = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("INSERT ss_bigram_ukr_pages(url_hash, bigramma) VALUES ('".$md5_uri."', '".$single_bigramma_1."')");
                }
            }
        }
        return $out_html;
    }

    private function bigrammaRus($out_html){
        $curr_uri = $_SERVER['REQUEST_URI'];
        if (strpos($curr_uri, "?") !== false){
            $curr_uri_no_query_string = preg_replace('#\?.+#s', '', $curr_uri);
        } else {
            $curr_uri_no_query_string = $curr_uri;
        }
        $md5_uri = md5($curr_uri_no_query_string);

        if (strpos($out_html, '<!--seoshield_formulas--kartochka-->') !== false || strpos($out_html, '<!--seoshield_formulas--kategoriya-->') !== false){

            $create_bigramma_table = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query('CREATE TABLE IF NOT EXISTS `ss_bigram_rus_pages`(
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `url_hash` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `bigramma` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`url_hash`)
            )');    

            if (!$create_bigramma_table){
                $out_html = str_replace('</body>', '<!--SEOSHIELD_INFO:SQL_ERROR:'.var_export($GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_error(), true).'--></body>', $out_html);
            } else {
                $result_db = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("SELECT bigramma FROM `ss_bigram_rus_pages` WHERE url_hash = '".$md5_uri."'");
                $bigramma_2 = array('ковролин',
                                    'пвх плитку',
                                    'гомогенный линолеум',
                                    'гомогенный винил',
                                    'спортивный линолеум',
                                    'линолеум');
                if ($result_db !== false && $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_num_rows($result_db) != 0){
                    while($res = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_fetch_array($result_db)){
                        $db_bigramma_2[] = $res['bigramma'];
                    }
                    $out_html = str_replace('{bigramma_2}', $db_bigramma_2[0], $out_html);
                } else {
                    shuffle($bigramma_2);
                    $single_bigramma_2 = array_pop($bigramma_2);
                    $out_html = str_replace('{bigramma_2}', $single_bigramma_2, $out_html);
                    $add_url_hash_query = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("INSERT ss_bigram_rus_pages(url_hash, bigramma) VALUES ('".$md5_uri."', '".$single_bigramma_2."')");
                }
            }
        }
        return $out_html;
    }

    private function ldJsonMarkup($out_html){
        $curr_uri = $_SERVER['REQUEST_URI'];
        $script = '<script type="application/ld+json">

[

    {

      "@context": "http://schema.org",

      "@type": "WebSite",

      "url": "https://mdtgroup.com.ua/",

      "potentialAction":

      {

          "@type": "SearchAction",

          "target": "https://mdtgroup.com.ua/search?q={search_term_string}",

          "query-input": "required name=search_term_string"

      }

    },

    {

        "@context": "http://schema.org",

        "@type": "Organization",

        "url": "https://mdtgroup.com.ua/",

        "logo": "https://mdtgroup.com.ua/wp-content/uploads/2021/mdt_logo.png",

        "name": "Эксперты в напольном покрытии MDTgroup",

        "email": "info@mdtgroup.com.ua",

        "address":

        {

            "@type": "PostalAddress",

            "streetAddress": "ул. Демеевская 13, офис 18",

            "addressLocality": "г. Киев"

        },

        "contactPoint": [{

            "@type": "ContactPoint",

            "telephone": "+38(050) 396 38 48",

            "contactType": "customer service"

        },{

            "@type": "ContactPoint",

            "telephone": "+38(044) 388 37 00",

            "contactType": "customer service"

        }],

        "sameAs" : [

            "https://www.facebook.com/mdtgroup",

            "https://www.instagram.com/mdt_group/"        

        ]

    },

    {

        "@context": "http://schema.org",

        "@type": "Store",

        "name": "Эксперты в напольном покрытии MDTgroup",

        "image": "https://mdtgroup.com.ua/wp-content/uploads/2021/mdt_logo.png",

        "openingHoursSpecification": [

            {

                "@type": "OpeningHoursSpecification",

                "dayOfWeek": [

                    "Понедельник",

                    "Вторник",

                    "Среда",

                    "Четверг",

                    "Пятница"

                ],

                "opens": "09:00",

                "closes": "18:00"

            },

            {

            "@type": "OpeningHoursSpecification",

            "dayOfWeek": [

                "Суббота",

                "Воскресенье"

            ],

            "opens": "Выходной",

            "closes": "Выходной"

            }

        ],

        "telephone": "+38(050) 396 38 48",

        "address": {

            "@type": "PostalAddress",

            "streetAddress": "ул. Демеевская 13, офис 18",

            "addressLocality": "Киев",

            "addressCountry": "Украина"

        }

    }

]

</script>';
    $out_html = str_replace('</head>',$script.'</head>',$out_html);

    if (strpos($out_html, '<!--dg_crumb_url:') !== false){

            $crumbs_snippet = 
'<script type="application/ld+json">
{
 "@context": "http://schema.org",
 "@type": "BreadcrumbList",
 "itemListElement": [';

            preg_match_all('#<!--dg_crumb_url:(.*?);;dg_crumb_name:(.*?)-->#s', $out_html, $finder);
            if (isset($finder[0]) && !empty($finder[0])){
                foreach ($finder[0] as $key => $value) {
                    $crumbs_snippet .= 
'{
 "@type": "ListItem",
 "position": '.($key+1).',
 "name": "'.$finder[2][$key].'",
 "item": "'.$finder[1][$key].'"
 },';
                }

                $crumbs_snippet .= 
']
}
</script>';

            }
            unset($finder);

            $crumbs_snippet = str_replace('},]', '}]', $crumbs_snippet);

            $out_html = str_replace('</head>', "\n".$crumbs_snippet."\n".'</head>', $out_html);
        }



    return $out_html;
    }

    private function imagesSitemap($out_html){
        $curr_uri = $_SERVER['REQUEST_URI'];
        $curr_host = $_SERVER['HTTP_HOST'];

        if (strpos($out_html, '<!--this_is_product-->') !== false && strpos($curr_uri, '?') === false ){

            $md5_uri = md5($curr_uri);
            $currentTime = time();
            $create_images_table = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query('CREATE TABLE IF NOT EXISTS `ss_images_for_sitemap`(
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `page_url_hash` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `page_url` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `image_url` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `time_created` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                INDEX (`page_url_hash`)
            )');

            if (!$create_images_table){
                $out_html = str_replace('</body>', '<!--SEOSHIELD_INFO:SQL_ERROR:'.var_export($GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_error(), true).'--></body>', $out_html);
            } else {
                $insert_values_string = "";
                preg_match_all('#<!--dg_img_in_sitemap:(.*?)-->#s',$out_html,$images);
                if (isset($images[1])&&!empty($images[1])){
                    $images[1]=array_unique($images[1]);
                    $insert_values_string .= "VALUES ";
                    foreach ($images[1] as $single_image_uri) {
                        $arr_images_urls[] = "('".$md5_uri."', '" . "https://mdtgroup.com.ua" . $curr_uri . "', '" . $single_image_uri . "', ".$currentTime.")";
                    }
                    $insert_values_string .= implode(',', $arr_images_urls);
                }
                unset($finder);

                $result_if_exist = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("SELECT time_created FROM `ss_images_for_sitemap` WHERE page_url_hash = '".$md5_uri."' limit 1");

                if ($result_if_exist !== false && $insert_values_string != ""){
                    $updateExisting = false;
                    if ($GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_num_rows($result_if_exist) != 0 ){
                        $resultRow = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_fetch_assoc($result_if_exist);
                        if (time() - $resultRow['time_created'] >= 86400*7){
                            $updateExisting = true;
                            $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("DELETE FROM ss_images_for_sitemap WHERE page_url_hash = '".$md5_uri."'");
                        }
                    }
                    if ($GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_num_rows($result_if_exist) == 0 || $updateExisting){
                        $add_url_hash_query = $GLOBALS['SEOSHIELD_CONFIG']['mysql']->mysql_query("INSERT ss_images_for_sitemap(page_url_hash, page_url, image_url, time_created) ".$insert_values_string);
                    }
                }
            }
        }

        return $out_html;
    }

}
