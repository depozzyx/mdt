<?php /**
 * Default Footer Template
 *
 */
?>
</div>
</div>
<footer>
    <div class="container d-flex align-items-start">
        <div class="footer__left wow fadeInUpDes" data-wow-offset="0" data-wow-delay="0.1s">
            <div class="logo__box d-flex">
                <a href="<?php the_permalink(lang_page_original_id(9)) ?>" class="logo">
                    <svg viewBox="0 0 93 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 10.1191V32.9258C2.78081 32.9258 5.6348 32.9258 8.41562 32.9258V19.4325V18.5525L0 10.1191Z" fill="#D63434"/>
                        <path d="M30.8101 10.1191V32.9258C28.0293 32.9258 25.1753 32.9258 22.3945 32.9258V19.4325V18.5525L30.8101 10.1191Z" fill="#D63434"/>
                        <path d="M27.8081 0L15.4408 14.5933L3.14671 0H0V10.1933L15.4408 26.2533L30.8817 10.1933V0H27.8081Z" fill="#B52525"/>
                        <path d="M46.107 8.94667L37.6914 0V33L46.107 24.3467V8.94667Z" fill="#D63434"/>
                        <path d="M50.0587 0H37.6914L46.107 8.94667H50.0587C60.3038 8.94667 60.3038 24.2733 50.0587 24.2733H46.107L37.6914 33H50.0587C71.0612 32.9267 71.0612 0.0733333 50.0587 0Z" fill="#B52525"/>
                        <path d="M76.0352 32.9995C78.7428 32.9995 81.7431 32.9995 84.4508 32.9995V9.01953L76.0352 17.4529V32.9995Z" fill="#D63434"/>
                        <path d="M68.3467 0C68.3467 2.42 68.3467 6.52667 68.3467 9.02H76.0305V17.4533L84.4461 9.02H92.2031C92.2031 6.52667 92.2031 2.49333 92.2031 0H68.3467Z" fill="#B52525"/>
                    </svg>
                </a>
            </div>
            <div class="footer__nav">
                <?php echo do_shortcode('[do_widget id=nav_menu-2]'); ?>
            </div>
        </div>
        <div class="footer__main d-flex flex-wrap align-items-start wow fadeInUpDes" data-wow-offset="0" data-wow-delay="0.2s">
            <div class="footer__col footer__col-1">
                <div class="footer__col-label">
                    <?php _e('Адреса', 'mdt'); ?>
                </div>
                <div class="footer__col-content">
                  <?php the_field('ct__address', lang_page_original_id(79)) ?>
                </div>
                <div class="actions__wrap d-flex">
                    <a href="<?php the_field('ct__gmaps', lang_page_original_id(79)) ?>" target="_blank" class="link__custom link__custom-small" rel="nofollow">
                      <?php _e('Відкрити на карті', 'mdt'); ?>
                    </a>
                </div>
            </div>
            <div class="footer__col footer__col-2">
                <div class="footer__col-label">
                    <?php _e('Телефони', 'mdt'); ?>
                </div>
                <div class="footer__col-content">
                    <p>
                        <a href="tel:<?php echo formatPhone(get_field('ct__ph-1', 79)) ?>"><?php the_field('ct__ph-1', 79) ?></a>
                    </p>
                    <p>
                        <a href="tel:<?php echo formatPhone(get_field('ct__ph-2', 79)) ?>"><?php the_field('ct__ph-2', 79) ?></a>
                    </p>
                </div>
            </div>
            <div class="footer__col footer__col-3">
                <div class="footer__col-label">
                    <?php _e('Напишіть нам', 'mdt'); ?>
                </div>
                <div class="footer__col-content">
                    <a href="mailto:<?php the_field('ct__email', 79) ?>"><?php the_field('ct__email', 79) ?></a>
                </div>
            </div>
            <div class="footer__col footer__col-1">
                <div class="footer__col-label">
                    <?php _e('Час роботи', 'mdt'); ?>
                </div>
                <div class="footer__col-content">
                  <?php the_field('ct__worktime', 79) ?>
                </div>
            </div>
            <div class="footer__col footer__col-2">
                <div class="footer__col-label">
                    <?php _e('Соц. мережі', 'mdt'); ?>
                </div>
                <div class="promo__social">
                    <ul>
                      <?php if(get_field('ct__facebook', 79)): ?>
                          <li>
                              <a href="<?php the_field('ct__facebook', 79); ?>" target="_blank" rel="nofollow">Facebook</a>
                          </li>
                      <?php endif; ?>
                      <?php if(get_field('ct__instagram', 79)): ?>
                          <li>
                              <a href="<?php the_field('ct__instagram', 79); ?>" target="_blank" rel="nofollow">Instagram</a>
                          </li>
                      <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="footer__col footer__col-3 d-flex">
                <a href="<?php the_permalink(lang_page_original_id(79)); ?>" class="btn__border d-flex align-items-center justify-content-center">
                    <?php echo get_the_title(lang_page_original_id(79)); ?>
                </a>
            </div>
            
            <div class="scroll__up-wrap hidden-md-down">
                <a href="#wrapper" class="scroll">
                    <div class="scroll__up-circle d-flex align-items-center justify-content-center">
                        <svg viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.2971 7.00024L5.5 2.79734L9.7029 7.00024L11 5.70319L5.5 0.20319L-1.13392e-07 5.70319L1.2971 7.00024Z" fill="white"/>
                        </svg>
                    </div>
                    <span><?php _e('Вгору', 'mdt'); ?></span>
                </a>
            </div>
            <div class="footer__bottom d-flex align-items-start">
                <div class="footer__col footer__col-1 hidden-md-down">
                    <div class="rights">
                        © <?php echo date('Y') ?> MDT
                    </div>
                </div>
                <div class="footer__col footer__col-2">
                    <div class="footer__col-content">
                        <a href="<?php echo get_permalink(lang_page_original_id(6516)) ?>"><?php echo get_the_title(lang_page_original_id(6516)) ?></a>
                    </div>
                </div>
                <div class="footer__col footer__col-3">
                    <div class="made">
                        Developed by <a target="_blank" href="https://ribs.one/">RIBS</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rights hidden-md-up">
            © <?php echo date('Y') ?> MDT
        </div>
    </div>
    <?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) { ?>
    <div class="container"><!--footers_block--></div>
<?php } ?>
</footer>
<div class="hidden">
    <div id="thanks-modal" class="modal__custom modal__custom-thanks">
        <a href="#" class="modal__close">
            <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="18.084" y="17.3779" width="1" height="24.5355" transform="rotate(135 18.084 17.3779)" fill="white"/>
                <rect x="0.734375" y="18.0845" width="1" height="24.5355" transform="rotate(-135 0.734375 18.0845)" fill="white"/>
            </svg>
        </a>
        <div class="modal__content">
            <div class="modal__custom-header">
                <div class="modal__custom-title"><?php the_field('th__title', 'option') ?></div>
                <div class="modal__custom-subtitle"><?php the_field('th__subtitle', 'option') ?></div>
            </div>
        </div>
    </div>
    <div class="modal__custom" id="order-modal">
        <a href="#" class="modal__close">
            <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="18.084" y="17.3779" width="1" height="24.5355" transform="rotate(135 18.084 17.3779)" fill="white"/>
                <rect x="0.734375" y="18.0845" width="1" height="24.5355" transform="rotate(-135 0.734375 18.0845)" fill="white"/>
            </svg>
        </a>
        <div class="modal__custom-header">
            <div class="modal__custom-title"></div>
        </div>
        <div class="modal__form">
            <?php
                switch(ICL_LANGUAGE_CODE){
                  case 'uk':
                      echo do_shortcode('[contact-form-7 id="6498" title="Модальна форма UA"]');
                      break;
                  case 'ru':
                      echo do_shortcode('[contact-form-7 id="6502" title="Модальна форма RU"]');
                      break;
                }
            ?>
        </div>
    </div>
</div>
<script src="<?php echo get_template_directory_uri();?>/assets/dist/main.js"></script>
<?php wp_footer(); ?>
</body>
</html>
