<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

if(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin') === false && $_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '.xsl') === false){
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')){
        if(file_exists('seoshield-client/main.php'))
        {
            include_once('seoshield-client/main.php');
            if(function_exists('seo_shield_start_cms')){
                seo_shield_start_cms();
            }
            if(function_exists('seo_shield_out_buffer')){
                ob_start('seo_shield_out_buffer');
            }
        }
    }
}

$per_page_redirs = array('/shop/pokrytiya-pod-beton/pokrytiya-pod-beton/' => '/ru/category/pokryttya-pidlogy/vinilovi-pokryttya-dlya-pidlogy/vinilove-pokryttya-pid-beton/',
'/project/' => '/ru/realizovannye-proekty/',
'/shop/?swoof=1&product_cat=kovrovaya-plitka' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/',
'/shop/proektnyj_vinil/' => '/ru/category/pokryttya-pidlogy/vinilovi-pokryttya-dlya-pidlogy/',
'/shop/flokirovannoe-kovrovoe-pokrytie-flotex/' => '/ru/category/pokryttya-pidlogy/flokovane-pidlogove-pokryttya/vysokotehnologichne-kylymove-pokryttya-forbo-flotex/',
'/shop/kovrovaya-plitka/forbo-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-forbo/',
'/shop/kovrovaya-plitka/milliken-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-milliken/',
'/shop/rulonnyj-kovrolin/' => '/ru/category/pokryttya-pidlogy/kovrolin/rulonnyj-kovrolin/',
'/shop/igloprobivnoj-kovrolin/' => '/ru/category/pokryttya-pidlogy/kovrolin/golkoprobyvnyj-kovrolin/',
'/shop/pletyonoe-pvh-pokrytie-dickson/' => '/ru/category/pokryttya-pidlogy/pvh-plytka/pletena-pvh-plytka-dickson/',
'/shop/falshpoly/' => '/ru/category/falshpidloga/',
'/shop/kovrovaya-plitka/forbo-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-forbo/',
'/shop/kovrovaya-plitka/pvh/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-pvh/',
'/shop/kovrovaya-plitka/incati/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-incati/',
'/shop/flokirovannoe-kovrovoe-pokrytie-flotex/forbo-flotex-by-starck/' => '/ru/category/pokryttya-pidlogy/flokovane-pidlogove-pokryttya/flotex-by-philippe-starck/',
'/projects/olx/' => '/ru/projects/servis-ogoloshen-olx/',
'/projects/ofis-kompanii-biosphere/' => '/ru/projects/ofis-kompaniyi-biosphere-corporation/',
'/projects/kommercheskoe-pomeshhenie-dtek-academy-unit-city-forbo-flotexb/' => '/ru/projects/dtek-academy-unit-city-kyyiv-2/',
'/projects/klinika-anacosma-forbo-sphera/' => '/ru/projects/klinika-plastychnoyi-hirurgiyi-ta-kosmetologiyi-anacosma-kyyiv/',
'/projects/restoran-flame-forbo-flotex/' => '/ru/projects/simejnyj-restoran-flame-2/',
'/projects/ofis-v-bts-igorevskij/' => '/ru/projects/ofis-v-bts-igorivskyj-2/',
'/projects/salon-krasoty-pied-de-poule-forbo-eternal/' => '/ru/projects/salon-krasy-pied-de-poule-harkiv/',
'/projects/salon-krasoty-igogo-ogo-forbo-allura-flex/' => '/ru/projects/salon-krasy-igogo-ogo/',
'/projects/28348/' => '/ru/projects/ofis-kompaniyi-datarobot-ua/',
'/projects/salon-krasoty-kedr-beauty-bar-forbo-effekta/' => '/ru/projects/salon-krasy-kedr-beauty-bar/',
'/projects/ofis-kinnarps-ukraine/' => '/ru/projects/ofis-kompaniyi-itera-consulting-bts-astarta/',
'/projects/ofis-innovecs/' => '/ru/projects/ofis-innovecs-2/',
'/projects/ofis-innohub/' => '/ru/projects/ofis-innohub/',
'/projects/apartamenty-effecta/' => '/ru/projects/kvartyra-light-comfort-dlya-zdachi-v-orendu/',
'/projects/apartamenty-click/' => '/ru/projects/kvartyra-family-nest/',
'/projects/ofis-weplay/' => '/ru/projects/ofis-weplay-media/',
'/projects/turisticheskij-ofis-travel-hub/' => '/ru/projects/ofis-travel-hub/',
'/projects/ofis-tetra-pak/' => '/ru/projects/ofis-kompaniyi-tetra-pak/',
'/projects/ofis-siemens/' => '/ru/projects/ofis-siemens-ukrayina/',
'/projects/ofis-partner-construction/' => '/ru/projects/ofis-partner-construction/',
'/projects/ofis-itera-consulting/' => '/ru/projects/ofis-kompaniyi-itera-consulting-bts-astarta-2/',
'/projects/ing-bank-pvh-forbo-allura-flex-kovrovoe-pokrytie-forbo-flotex/' => '/ru/projects/ofis-ing-bank/',
'/projects/ofis-wargaming-forbo-eternal/' => '/ru/projects/ofis-wargaming/',
'/projects/ofis-zeo-alliance-rulonnoe-pvh-forbo-sarlon/' => '/ru/projects/ofis-zeo-alliance/',
'/projects/ofis-ceo-club-ukraine-flotex-colour-pvh-plitka-allura-colour-kovrovaya-plitka-westbond/' => '/ru/projects/seo-klub/',
'/projects/ofis-data-robot-rulonnoe-pvh-pokrytie-forbo-kollektsiya-sarlon/' => '/ru/projects/ofis-kompaniyi-datarobot-ua/',
'/projects/ofis-bacardi-martini-ukraine-forbo-flotex-forbo-allura/' => '/ru/projects/ofis-zikzak-architects/',
'/projects/ofis-zikzak/' => '/ru/projects/ofis-zikzak-architects/',
'/projects/restoran-momo-pvh-pokrytie-forbo-allura-wood/' => '/ru/projects/restoran-momo/',
'/projects/ofis-samsung-kovrovoe-pokrytie-pvh-kollektsiya-los-angeles/' => '/ru/projects/ofis-samsung/',
'/projects/ofis-ffu-forbo-eternal/' => '/ru/projects/ofis-federatsiyi-futbolu-ukrayiny/',
'/projects/rulonnoe-kovrovoe-pokrytie-orotex/' => '/ru/projects/kinoteatr-boomer/',
'/projects/ofis-ultra-kovrovaya-plitka-milliken/' => '/ru/projects/ofis-kompaniyi-ultra/',
'/projects/ofis-ukrborg-rulonnoe-kovrovoe-pokrytie-orotex/' => '/ru/projects/ofis-ukrborg/',
'/projects/rulonnoe-pvh-pokrytie-forbo-kollektsiya-eternal/' => '/ru/projects/ofis-inzhenernoyi-kompaniyi-cpi-kyyiv/',
'/projects/sport-klub-nolimits-v-trts-atmosfera-rulonnoe-pvh-forbo-eternal/' => '/ru/projects/sportzal-nolimits-v-tts-atmosfera/',
'/projects/plitochnoe-kovrovoe-pokrytie-pvh-kollektsiya-los-angeles/' => '/ru/projects/ofis-kompaniyi-itera-consulting-bts-astarta/',
'/projects/ofis-v-kieve-pvh-pokrytie-allura-click-kovrovaya-plitka-forbo/' => '/ru/projects/ofis-kompaniyi-ukrgaz/',
'/projects/plitochnoe-kovrovoe-pokrytie-pvh-kollektsiya-nairobi/' => '/ru/projects/ofis-kompaniyi-reckitt-benckiser-ukraine-2/',
'/projects/rulonnoe-kovrovoe-pokrytie-ideal-creative-flooring/' => '/ru/projects/ofis-v-bts-parus/',
'/projects/ofis-utk-rulonnoe-pvh-pokrytie-forbo-eternal/' => '/ru/projects/ofis-kompaniyi-utk/',
'/projects/ofis-jll-pvh-pokrytie-beauflor-kollektsiya-podium-pro-30/' => '/ru/projects/ofis-jll-kyyiv/',
'/projects/ofis-danone-rulonnoe-pvh-pokrytie-forbo-eternal/' => '/ru/projects/ofis-kompaniyi-danone/',
'/projects/ofis-colgate-palmolive-plitochnoe-kovrovoe-pokrytie-pvh/' => '/ru/projects/ofis-kompaniyi-colgate-palmolive/',
'/projects/ofis-be-consulting/' => '/ru/projects/ofis-be-consulting/',
'/projects/ozdorovitelnyj-tsentr-fortis-forbo-eternal/' => '/ru/projects/fitnes-tsentr-fortis-harkiv/',
'/projects/magazin-vdone-eternal/' => '/ru/projects/magazyn-vd-one/',
'/projects/kovrovaya-plitka-forbo-kollektsiya-arran/' => '/ru/projects/ofis-kompaniyi-reckitt-benckiser-ukraine/',
'/projects/klinika-reproduktivnoj-meditsiny-implant-forbo-tessera/' => '/ru/projects/klinika-reproduktyvnoyi-medytsyny-implant-harkiv/',
'/projects/bank-kredit-dnepro-pvh-pokrytie-podium-pro/' => '/ru/projects/bank-kredyt-dnipro/',
'/projects/tapibel-kollektsiya-cobalt/' => '/ru/projects/ofis-kompaniyi-itera-consulting-bts-astarta-2/',
'/projects/it-company/' => '/ru/projects/ofis-it-kompaniyi/',
'/projects/concept-store-so-dodo-forbo-sphera/' => '/ru/projects/concept-store-so-dodo/',
'/projects/mirotex-rulonnyj-pvh-eternal/' => '/ru/projects/viddilennya-i-ofis-pumb/',
'/projects/restoran-zhiguli/' => '/ru/projects/restoran-zhyguli-kyyiv/',
'/projects/ofis-innovecs-kovrovoe-pokrytie-tapibel-kollektsiya-gamma/' => '/ru/projects/ofis-innovecs-2/',
'/projects/pumb/' => '/ru/projects/viddilennya-i-ofis-pumb-2/',
'/projects/ferrero/' => '/ru/projects/ofis-kompaniyi-ferrero/',
'/projects/atsmd-medoks/' => '/ru/projects/klinika-atsmd-medoks/',
'/product/tessera-basis/' => '/ru/product/forbo-tessera-basis/',
'/product/tessera-create-space-1/' => '/ru/product/forbo-tessera-create-space-1/',
'/product/forbo-tessera-cloudscape/' => '/ru/product/forbo-tessera-cloudscape/',
'/product/tessera-create-space-2/' => '/ru/product/forbo-tessera-create-space-2/',
'/product/tessera-diffusion/' => '/ru/product/forbo-tessera-diffusion/',
'/product/tessera-inline/' => '/ru/product/forbo-tessera-layout-2/',
'/product/forbo-tessera-mix/' => '/ru/product/forbo-tessera-mix/',
'/product/tessera-alignment/' => '/ru/product/forbo-tessera-alignment/',
'/product/tessera-arran/' => '/ru/product/forbo-tessera-arran/',
'/product/tessera-barcode/' => '/ru/product/forbo-tessera-barcode/',
'/product/tessera-contour/' => '/ru/product/forbo-tessera-contour/',
'/product/incati-myrage/' => '/ru/product/incati-myriad/',
'/product/incati-granite/' => '/ru/product/incati-granite/',
'/product/incati-lisbon/' => '/ru/product/incati-lisbon/',
'/product/incati-metal-x/' => '/ru/product/incati-metal-x/',
'/product/incati-myrage/' => '/ru/product/incati-myrage/',
'/product/incati-president/' => '/ru/product/incati-president-e/',
'/product/incati-shades/' => '/ru/product/incati-shades/',
'/product/incati-vintage/' => '/ru/product/incati-vintage/',
'/product/incati-zamba/' => '/ru/product/incati-zamba/',
'/product/incati-atlas/' => '/ru/product/incati-atlas/',
'/product/incati-cobalt/' => '/ru/product/incati-cobalt/',
'/product/incati-cobalt-art/' => '/ru/product/incati-cobalt-art/',
'/product/incati-cobalt-lines/' => '/ru/product/incati-cobalt-lines/',
'/product/incati-country/' => '/ru/product/incati-country/',
'/product/incati-country-lines/' => '/ru/product/incati-country-lines/',
'/product/pvh-cross/' => '/ru/product/pvh-plate-en-van-heusde-bv-cross/',
'/product/pvh-fiesta/' => '/ru/product/pvh-plate-en-van-heusde-bv-fiesta/',
'/product/pvh-orinoco/' => '/ru/product/pvh-plate-en-van-heusde-bv-orinoco/',
'/product/pvh-reflex/' => '/ru/product/pvh-plate-en-van-heusde-bv-reflex/',
'/product/pvh-strike/' => '/ru/product/pvh-plate-en-van-heusde-bv-strike/',
'/product/pvh-viva/' => '/ru/product/pvh-plate-en-van-heusde-bv-viva/',
'/product/fixation-tilt/' => '/ru/product/milliken-fixation-tilt/',
'/product/clerkenwell-angled-walk/' => '/ru/product/milliken-clerkenwell-angled-walk/',
'/product/crafted-series-modern-maker/' => '/ru/product/milliken-crafted-series-modern-maker/',
'/product/consequences-2-0-sequel/' => '/ru/product/milliken-consequence-2-0-sequel/',
'/product/consequences-2-0-upshot/' => '/ru/product/milliken-consequence-2-0-upshot/',
'/product/clerkenwell-ely-place/' => '/ru/product/milliken-clerkenwell-ely-place/',
'/product/clerkenwellthree-corners/' => '/ru/product/milliken-clerkenwell-three-corners/',
'/product/clerkenwelltime-piece/' => '/ru/product/milliken-clerkenwell-time-piece/',
'/product/clerkenwell-travelling-line/' => '/ru/product/milliken-clerkenwell-travelling-line/',
'/product/clerkenwelltriangular-path/' => '/ru/product/milliken-clerkenwell-triangular-path/',
'/product/clerkenwellcircle-top-view/' => '/ru/product/milliken-clerkenwell-circle-top-view/',
'/product/dissident-2-0-transitions/' => '/ru/product/milliken-dissident-2-0-transitions/',
'/product/milliken-facet/' => '/ru/product/milliken-facet/',
'/product/fixation-accents/' => '/ru/product/milliken-formwork-accents/',
'/product/fixation-trio/' => '/ru/product/milliken-fixation-trio/',
'/product/fixation-zigzag/' => '/ru/product/milliken-fixation-zigzag/',
'/product/fixation-brights/' => '/ru/product/milliken-fixation-brights/',
'/product/fixation-neutrals/' => '/ru/product/milliken-fixation-neutrals/',
'/product/milliken-glazed-clay/' => '/ru/product/milliken-glazed-clay/',
'/product/naturally-drawndrawing-in-ink/' => '/ru/product/milliken-naturally-drawn-drawing-in-ink/',
'/product/naturally-drawnhand-sketched/' => '/ru/product/milliken-naturally-drawn-hand-sketched/',
'/product/naturally-drawnhand-sketched-transition/' => '/ru/product/milliken-naturally-drawn-hand-sketched-transition/',
'/product/naturally-drawnwatercolour-lesson/' => '/ru/product/milliken-naturally-drawn-watercolour-lesson/',
'/product/nordic-storieshidden-plains/' => '/ru/product/milliken-nordic-stories-hidden-plains/',
'/product/nordic-storiesisograd/' => '/ru/product/milliken-nordic-stories-isograd/',
'/product/milliken-nordic-stories-planks-isograd/' => '/ru/product/milliken-nordic-stories-planks-isograd-25x-100-cm/',
'/product/nordic-storiestectonic/' => '/ru/product/milliken-nordic-stories-tectonic/',
'/product/tracing-landscapes-field-study/' => '/ru/product/milliken-tracing-landscapes-field-study-2/',
'/product/tracing-landscapes-geography-lessons/' => '/ru/product/milliken-tracing-landscape-geography-lessons/',
'/product/per-contra-2-0in-contrast/' => '/ru/product/milliken-per-contra-2-0-in-contrast/',
'/product/per-contra-2-0on-the-contrary/' => '/ru/product/milliken-per-contra-2-0-on-the-contrary/',
'/shop/kovrovaya-plitka/forbo-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-forbo/',
'/shop/kovrovaya-plitka/incati/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-incati/',
'/shop/kovrovaya-plitka/pvh/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-pvh/',
'/shop/kovrovaya-plitka/milliken-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-milliken/',
'/shop/flokirovannoe-kovrovoe-pokrytie-flotex/' => '/ru/category/pokryttya-pidlogy/flokovane-pidlogove-pokryttya/vysokotehnologichne-kylymove-pokryttya-forbo-flotex/',
'/ru/product/forbo-flotex-triad/' => '/ru/product/forbo-flotex-triad/',
'/product/floral/' => '/ru/product/forbo-flotex-vision-floral/',
'/product/image/' => '/ru/product/forbo-flotex-vision-image/',
'/product/lines/' => '/ru/product/forbo-flotex-vision-lines/',
'/product/naturals/' => '/ru/product/forbo-flotex-vision-naturals/',
'/product/pattern-vision/' => '/ru/product/forbo-flotex-vision-pattern/',
'/product/shape-vision/' => '/ru/product/forbo-flotex-vision-shape/',
'/product/wood/' => '/ru/product/forbo-flotex-wood/',
'/product/ecosystems/' => '/ru/product/forbo-flotex-vision-ecosystems/',
'/product/heritage/' => '/ru/product/forbo-flotex-vision-heritage/',
'/product/box-cross/' => '/ru/product/forbo-flotex-box-cross-2/',
'/product/forbo-flotex-classic-calgary/' => '/ru/product/forbo-flotex-calgary/',
'/product/forbo-classic-flotex-canyon/' => '/ru/product/forbo-flotex-canyon/',
'/product/concrete/' => '/ru/product/forbo-flotex-concrete/',
'/product/metro/' => '/ru/product/forbo-flotex-metro/',
'/product/penang/' => '/ru/product/forbo-flotex-penang/',
'/product/wonderlab/' => '/ru/product/forbo-flotex-vision-wonderlab-2/',
'/shop/pletyonoe-pvh-pokrytie-dickson/' => '/ru/category/pokryttya-pidlogy/pvh-plytka/pletena-pvh-plytka-dickson/',
'/product/dickson-be-easy/' => '/ru/product/dickson-be-easy/',
'/product/dickson-be-tweed/' => '/ru/product/dickson-be-tweed/',
'/product/lano-optima-essential/' => '/ru/product/lano-optima-essential/',
'/product/lano-granit/' => '/ru/product/lano-granit/',
'/product/lano-bergamo/' => '/ru/product/lano-bergamo/',
'/product/forbo-forte-graphic-reef-2/' => '/ru/product/forbo-forte-graphic-reef-2/',
'/product/forbo-forte-graphic-reef-2/' => '/ru/product/forbo-forte-graphic-reef/',
'/product/forbo-forte-graphic-rice-2/' => '/ru/product/forbo-forte-graphic-rice/',
'/shop/igloprobivnoj-kovrolin/' => '/ru/category/pokryttya-pidlogy/kovrolin/golkoprobyvnyj-kovrolin/',
'/product/forbo-akzent/' => '/ru/product/forbo-akzent/',
'/product/forbo-sphera-element/' => '/ru/product/forbo-sphera-element/',
'/product/forbo-sphera-energetic/' => '/ru/product/forbo-sphera-energetic/',
'/product/forbo-sphera-evolution/' => '/ru/product/forbo-sphera-evolution/',
'/product/forbo-colorex-ec/' => '/ru/product/forbo-colorex-ec/',
'/product/forbo-colorex-sd/' => '/ru/product/forbo-colorex-sd/',
'/product/brush/' => '/ru/product/forbo-coral-brush/',
'/product/classic/' => '/ru/product/forbo-coral-classic/',
'/product/duo/' => '/ru/product/forbo-coral-duo/',
'/product/grip-hd/' => '/ru/product/forbo-coral-grip/',
'/product/grip-md/' => '/ru/product/forbo-coral-grip-md/',
'/product/luxe/' => '/ru/product/forbo-coral-luxe/',
'/product/welcome/' => '/ru/product/forbo-coral-welcome/',
'/shop/kovrovaya-plitka-v-plankah/toucan-t-ru/' => '/ru/category/pokryttya-pidlogy/kylymova-plytka/kylymova-plytka-toucan-t/',
'/shop/rulonnyj-kovrolin/tapibel/' => '/ru/category/pokryttya-pidlogy/kovrolin/kovrolin-tapibel/',
'/product/pvh-cross/' => '/ru/product/pvh-plate-en-van-heusde-bv-cross/',
'/product/pvh-fiesta/' => '/ru/product/pvh-plate-en-van-heusde-bv-fiesta/',
'/product/pvh-orinoco/' => '/ru/product/pvh-plate-en-van-heusde-bv-orinoco/',
'/product/pvh-reflex/' => '/ru/product/pvh-plate-en-van-heusde-bv-reflex/',
'/product/pvh-strike/' => '/ru/product/pvh-plate-en-van-heusde-bv-strike/',
'/product/pvh-viva/' => '/ru/product/pvh-plate-en-van-heusde-bv-viva/',
'/product/podvesnye-potolki-armstrong/' => '/ru/product/armstrong-metal/',
'/shop/vhodnye-sistemy-gryazezashhity/' => '/ru/category/pokryttya-pidlogy/brudozahysni-kylymy/');

if (isset($per_page_redirs[$_SERVER['REQUEST_URI']])){
	header('Location: //' . $_SERVER['HTTP_HOST'] . $per_page_redirs[$_SERVER['REQUEST_URI']], true, 301);
	die();
}

$cities_arr = array('kiev', 'kharkov', 'odessa', 'dnepr', 'mariupol', 'zaporozhe', 'lvov', 'krivoj-rog', 'nikolaev', 'vinnitsa', 'herson', 'poltava', 'chernigov', 'cherkassy', 'zhitomir', 'sumy', 'hmelnitskij', 'rovno', 'kropivnitskij', 'chernovtsy', 'kremenchug', 'ternopol', 'ivano-frankovsk', 'belaya-tserkov', 'lutsk', 'melitopol', 'nikopol', 'berdyansk', 'uzhgorod', 'kamenets-podolskij', 'pavlograd', 'severodonetsk', 'kamenskoe', 'kramatorsk', 'slavyansk', 'brovary', 'lisichansk', 'aleksandriya', 'konstantinovka');

foreach ($cities_arr as $city) {
    if ($_SERVER['REQUEST_URI'] == '/ru/dostavka-i-oplata/'.$city.'/'){
        $_SERVER['REQUEST_URI'] = '/ru/dostavka-i-oplata/';
        $_SERVER['curr_city'] = $city;
    }
}


/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
