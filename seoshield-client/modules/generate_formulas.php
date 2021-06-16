<?php
/***
PLACE THIS FILE INTO /seoshield-client/modules/ DIRECTORY
*/
//error_reporting(E_ERROR);

if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class SeoShieldModule_generate_formulas_config extends SeoShieldModule_generate_formulas
{
	public function setup_variables($out_html){

		$curr_uri = $_SERVER['REQUEST_URI'];
		$curr_host = $_SERVER['HTTP_HOST'];

		if (strpos($curr_uri, '/ru/') !== false){
			$out_html = str_replace('</head>', '<!--seoshield--version--site--rus-->'."\n".'</head>', $out_html);
		} else {
			$out_html = str_replace('</head>', '<!--seoshield--version--site--ukr-->'."\n".'</head>', $out_html);
		}

		if (strpos($curr_uri, '/novosti/') !== false || strpos($curr_uri, '/news/') !== false){
			$out_html = str_replace('</head>', '<!--seoshield_formulas--novosti-->'."\n".'</head>', $out_html);
		}

		$mas_service_ua = array('/about-us/', '/dostavka-i-oplata/', '/garantyya/', '/reviews/', '/umovy-spivpratsi/', '/optovym-pokuptsyam/');
		if (in_array($curr_uri, $mas_service_ua) && strpos($out_html, '<!--seoshield--version--site--ukr-->') !== false){
			$out_html = str_replace('</head>', '<!--seoshield_formulas--sluzhebnye-->'."\n".'</head>', $out_html);
		}

		$mas_service_rus = array('/ru/about-us/', '/ru/dostavka-i-oplata/', '/ru/garantiya/', '/ru/otzyvy/', '/ru/usloviya-sotrudnichestva/', '/ru/optovym-pokupatelyam/');
		if (in_array($curr_uri, $mas_service_rus) && strpos($out_html, '<!--seoshield--version--site--rus-->') !== false){
			$out_html = str_replace('</head>', '<!--seoshield_formulas--sluzhebnye-->'."\n".'</head>', $out_html);
		}

		if (strpos($out_html, '<!--this_is_product-->') !== false){
			$out_html = str_replace('</head>', '<!--seoshield_formulas--kartochka-->'."\n".'</head>', $out_html);
		}

		if (strpos($out_html, '<!--isset_listing_page-->') !== false){
			$out_html = str_replace('</head>', '<!--seoshield_formulas--kategoriya-->'."\n".'</head>', $out_html);
		}

		return $out_html;
	}

	function set_robots_noindex_nofollow($out_html){
		if (strpos($out_html, '<meta name="robots" content="noindex, nofollow" />') === false 
			&& strpos($out_html, "<meta name='robots' content='noindex, nofollow' />") === false){
			if (preg_match('#<meta[^>]*?name=[\'\"]?robots[\'\"]?[^>]*?>#si', $out_html)){
				$out_html = preg_replace('#<meta[^>]*?name=[\'\"]?robots[\'\"]?[^>]*?>#si', '<meta name="robots" content="noindex, nofollow" />', $out_html);
			} else {
				$out_html = str_replace('</head>', '<meta name="robots" content="noindex, nofollow" />'."\n".'</head>', $out_html);
			}
		}
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

	function h1_formulas_generation($out_html)
	{
		$curr_h1 = "";
		preg_match('#<h1[^>]*?>(.*?)<\/h1>#s', $out_html, $finder);
		if (isset($finder[0]) && !empty($finder[1])){
			$curr_h1 = strip_tags($finder[1]);
			$curr_h1 = trim($curr_h1);
		}

		if (file_exists(SEOSHIELD_ROOT_PATH."/data/static_meta.cache.php")){
			if(!isset($GLOBALS['SEOSHIELD_DATA']['static_meta']))
				$this->get_static_meta();

			if (isset($GLOBALS['SEOSHIELD_DATA']['static_meta']['h1'])){
				$curr_h1 = $GLOBALS['SEOSHIELD_DATA']['static_meta']['h1'];
			}
		}

		return $curr_h1;
	}

}