<!DOCTYPE html>
<html>
<head>
    <meta name="facebook-domain-verification" content="xeklui2jnzq6dzarc9pk9s5jdzi6a8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/dist/style.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/static/css/catalog.css">
    <?php wp_head(); ?>
    <!-- Google Tag Manager -->
    <!-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NNPGVT8');</script> -->
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-55X2HCV');</script>
    <!-- End Google Tag Manager -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <meta name="google-site-verification" content="qAvpvh2ErdUEjU0SZVEOAExIgyU8MXN7Aclp78GND4w"/>
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-41490873-1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-41490873-1');
      </script> -->
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '264057502273386');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=264057502273386&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <?php /*AWEB #254727 START*/ ?>
    <style>
        .header .container {
            /*background-color: rgba(255,255,255,.85); !important;   */
            position: fixed;
            z-index: 100000000;
            -moz-transition: 400ms linear;
            -o-transition: 400ms linear;
            -ms-transition: 400ms linear;
        }

        .dopstyle_header {
            background: rgba(255, 255, 255, .45);
            box-shadow: 4px 4px 10px rgb(0 0 0 / 15%);
            -webkit-transition: 400ms linear;
            -moz-transition: 400ms linear;
            -o-transition: 400ms linear;
            -ms-transition: 400ms linear;
            transition: 400ms linear;
        }

        .dopstyle_header .lang__list ul li span, .dopstyle_header .lang__list ul li a, .dopstyle_header .main__nav ul li > a, .dopstyle_header .lang__list ul li a:after {
            color: #575757 !important;
        }
    </style>

    <script>
        window.addEventListener('scroll', function (e) {
            var position = document.documentElement.scrollTop;
            var begin = 35;
            //var bloc=$('.header');
            var bloc = document.getElementsByClassName("container")[0];
            if (position > begin) {
                //bloc.addClass('dopstyle_header');
                bloc.classList.add("dopstyle_header");
            } else {
                //bloc.removeClass('dopstyle_header');
                bloc.classList.remove("dopstyle_header");
            }
        });
    </script>
    <?php /*AWEB #254727 END*/ ?>

</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-55X2HCV"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Google Tag Manager (noscript) -->
<!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NNPGVT8"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->
<!-- End Google Tag Manager (noscript) -->


<div id="wrapper" class="wrapper wow fadeIn" data-wow-delay="0.1s" style="visibility: hidden">
    <div class="header wow fadeInDownDes" data-wow-delay="0.1s">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="logo__box d-flex">
                <a href="<?php the_permalink(lang_page_original_id(9)) ?>" class="logo">
                    <svg viewBox="0 0 93 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 10.1191V32.9258C2.78081 32.9258 5.6348 32.9258 8.41562 32.9258V19.4325V18.5525L0 10.1191Z"
                              fill="#D63434"/>
                        <path d="M30.8101 10.1191V32.9258C28.0293 32.9258 25.1753 32.9258 22.3945 32.9258V19.4325V18.5525L30.8101 10.1191Z"
                              fill="#D63434"/>
                        <path d="M27.8081 0L15.4408 14.5933L3.14671 0H0V10.1933L15.4408 26.2533L30.8817 10.1933V0H27.8081Z"
                              fill="#B52525"/>
                        <path d="M46.1031 8.94667L37.6875 0V33L46.1031 24.3467V8.94667Z" fill="#D63434"/>
                        <path d="M50.0548 0H37.6875L46.1031 8.94667H50.0548C60.2999 8.94667 60.2999 24.2733 50.0548 24.2733H46.1031L37.6875 33H50.0548C71.0572 32.9267 71.0572 0.0733333 50.0548 0Z"
                              fill="#B52525"/>
                        <path d="M76.0352 32.9995C78.7428 32.9995 81.7431 32.9995 84.4508 32.9995V9.01953L76.0352 17.4529V32.9995Z"
                              fill="#D63434"/>
                        <path d="M68.3477 0C68.3477 2.42 68.3477 6.52667 68.3477 9.02H76.0315V17.4533L84.4471 9.02H92.2041C92.2041 6.52667 92.2041 2.49333 92.2041 0H68.3477Z"
                              fill="#B52525"/>
                    </svg>
                </a>
            </div>
            <div class="nav__bars hidden-md-up">
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <div class="main__nav">
                <div class="main__nav-header d-flex align-items-center hidden-md-up">
                    <div class="lang__list">
                        <ul>
                            <?php icl_post_languages(); ?>
                        </ul>
                    </div>
                    <a href="#" class="close__nav">
                        <svg viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.679688" y="20.4668" width="28.9441" height="1"
                                  transform="rotate(-45 0.679688 20.4668)" fill="#3C3C3C"/>
                            <rect x="1.53516" width="28.9441" height="1" transform="rotate(45 1.53516 0)"
                                  fill="#3C3C3C"/>
                        </svg>
                    </a>
                </div>
                <div class="main__nav-box">
                    <?php
                    $nav = wp_get_nav_menu_items(32, [
                        'output_key' => 'menu_order',
                    ]);
                    ?>
                    <div class="add__nav-box hidden-md-up">
                        <ul>
                            <?php foreach ($nav as $nav_item): ?>
                                <?php if ($nav_item->object === 'category' && ($nav_item->post_name === 'katalog-tovariv' || $nav_item->post_name === 'katalog-tovarov-2')): ?>
                                    <li class="has-submenu <?php if (is_page($nav_item->object_id)) echo 'active'; ?>">
                                        <a href="<?php echo get_permalink(mdt_catalog_root_id()); ?>">
                                            <span><?php echo $nav_item->title; ?></span>
                                            <i class="icon__arrow">
                                                <svg viewBox="0 0 8 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.05666 0.236328L4 3.29298L0.943344 0.236328L0 1.17964L4 5.17964L8 1.17964L7.05666 0.236328Z"
                                                          fill="white"/>
                                                </svg>
                                            </i>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="<?php if (is_page($nav_item->object_id)) echo 'active'; ?>"><a
                                                href="<?php echo $nav_item->url; ?>"><?php echo $nav_item->title; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <ul>
                        <?php foreach ($nav as $nav_item): ?>
                            <?php if ($nav_item->object === 'category' && ($nav_item->post_name === 'katalog-tovariv' || $nav_item->post_name === 'katalog-tovarov-2')): ?>
                                <li class="has-submenu <?php if (is_page($nav_item->object_id)) echo 'active'; ?>">
                                    <a href="#">
                                        <span><?php echo $nav_item->title; ?></span>
                                        <i class="icon__arrow">
                                            <svg viewBox="0 0 8 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.05666 0.236328L4 3.29298L0.943344 0.236328L0 1.17964L4 5.17964L8 1.17964L7.05666 0.236328Z"
                                                      fill="white"/>
                                            </svg>
                                        </i>
                                    </a>
                                    <div class="menu-nav__box">
                                        <div class="nav__box-wrap d-flex">
                                            <div class="nav__box-left d-flex">
                                                <div class="nav__box-col nav__box-col-1">
                                                    <div class="nav__box-title">
                                                        <?php _e('КАТЕГОРІЇ', 'mdt'); ?>
                                                    </div>
                                                    <div class="nav__cat-box">
                                                        <?php $cat_chs = mdt_top_level_categories(); ?>
                                                        <div class="nav__cat-scroll scrollbar-dynamic">
                                                            <div class="nav__cat-list">
                                                                <?php $k = 1;
                                                                foreach ((array)$cat_chs as $ch) : ?>
                                                                    <div class="nav__cat-item">
                                                                        <?php $sub__cats = mdt_category_children($ch->ID); ?>
                                                                        <?php if (count($sub__cats) > 0): ?>
                                                                            <div class="nav__cat-item-header">
                                                                                <a href="#">
                                                                                    <?php echo get_the_title($ch); ?>
                                                                                </a>
                                                                                <i class="icon__arrow">
                                                                                    <svg viewBox="0 0 9 6" fill="none"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                              clip-rule="evenodd"
                                                                                              d="M4.5 6L-3.71752e-07 1.74765L0.984506 0.857143L4.5 4.17917L8.01549 0.857142L9 1.74765L4.5 6Z"
                                                                                              fill="#343434"/>
                                                                                    </svg>
                                                                                </i>
                                                                            </div>
                                                                            <div class="nav__cat-item-inside">
                                                                                <ul>
                                                                                    <li>
                                                                                        <a href="<?php the_permalink($ch) ?>">
                                                                                            <?php _e('Всі товари', 'mdt'); ?>
                                                                                        </a>
                                                                                    </li>
                                                                                    <?php foreach ($sub__cats as $sub__cat): ?>
                                                                                        <li>
                                                                                            <a href="<?php the_permalink($sub__cat) ?>">
                                                                                                <?php echo get_the_title($sub__cat); ?>
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php endforeach; ?>
                                                                                </ul>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div class="nav__cat-item-header">
                                                                                <a href="<?php the_permalink($ch) ?>">
                                                                                    <?php echo get_the_title($ch); ?>
                                                                                </a>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php $k++; endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nav__box-right hidden-md-down">
                                                <div class="static__nav">
                                                    <?php echo do_shortcode('[do_widget id=nav_menu-4]'); ?>
                                                </div>
                                                <div class="nav__box-right-action">
                                                    <div class="nav__box-right-action-text">
                                                        <?php the_field('nav__descr', 'option'); ?>
                                                    </div>
                                                    <div class="actions__wrap d-flex">
                                                        <a href="#order-modal"
                                                           data-fancybox=""
                                                           data-touch="false"
                                                           data-modal-title="<?php the_field('modal__title-4', 'option'); ?>"
                                                           data-modal-src="<?php the_field('modal__src-4', 'option'); ?>"
                                                           class="btn__border d-flex align-items-center justify-content-center">
                                                            <?php the_field('nav__link-title', 'option'); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php else: ?>
                                <li class="<?php if (is_page($nav_item->object_id)) echo 'active'; ?>"><a
                                            href="<?php echo $nav_item->url; ?>"><?php echo $nav_item->title; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="nav__box-right hidden-md-up">
                        <div class="static__nav">
                            <?php echo do_shortcode('[do_widget id=nav_menu-4]'); ?>
                        </div>
                        <div class="nav__box-right-action">
                            <div class="nav__box-right-action-text">
                                <?php the_field('nav__descr', 'option'); ?>
                            </div>
                            <div class="actions__wrap d-flex">
                                <a href="#order-modal"
                                   data-fancybox=""
                                   data-touch="false"
                                   data-modal-title="<?php the_field('modal__title-4', 'option'); ?>"
                                   data-modal-src="<?php the_field('modal__src-4', 'option'); ?>"
                                   class="btn__border d-flex align-items-center justify-content-center">
                                    <?php the_field('nav__link-title', 'option'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php /*AWEB #254727 START*/ ?>
            <div class="lang__list">
                <span style="color:#f0f0f0"><ul><li><a href="tel:+38(050) 396 38 48">+38(050) 396 38 48</a></li> <li><a href="tel:+38(044) 388 37 00">+38(044) 388 37 00</a></li></ul></span>
            </div>
            <?php /*AWEB #254727 END*/ ?>
            <div class="lang__list">
                <ul>
                    <?php icl_post_languages(); ?>
                </ul>
            </div>
        </div>
        <?php
        if (function_exists("http_response_code")) {
            $status = http_response_code();
            if (isset($status) && $status == '404') {
                echo '<div  style="text-align:center;"><h1>К сожалению, страница не найдена</h1><br><a href="' . get_permalink(lang_page_original_id(9)) . '">На главную</a></div>';
            }
        }
        ?>
    </div>
    <div class="content">
