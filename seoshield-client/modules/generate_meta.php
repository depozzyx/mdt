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

        if ((strpos($out_html, '<!--seoshield_formulas--sluzhebnye-->') !== false 
        || strpos($out_html, '<!--seoshield_formulas--novosti-->') !== false)
        && preg_match('#<meta[^>]*?name=[\'\"]?description[^>]*?>#s', $out_html)){
            $out_html = preg_replace('#<meta[^>]*?name=[\'\"]?description[^>]*?>#s', '', $out_html);
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
}
