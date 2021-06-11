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
}
