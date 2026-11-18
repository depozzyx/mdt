<?php /*Template Name: Home page*/ ?>
<?php get_header(); ?>
<div class="section sc__promo">
    <div class="promo__slider-box">
        <div class="promo__slider">
            <?php $img_sl = get_field('promo__slider'); ?>
            <?php if($img_sl): ?>
                <?php $j = 1; foreach( $img_sl as $image_sl ): ?>
                    <div class="promo__item">
                        <div class="promo__item-bg" style="background-image: url(<?php echo esc_url($image_sl['sizes']['full']); ?>)"></div>
                    </div>
              <?php $j++; endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="promo__content-box">
            <div class="container d-flex align-items-center">
                <div class="promo__content-wrap">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-9 col-md-12 col-sm-12 col-12">
                            <div class="promo__content">
                                <div class="promo__item-title wow fadeInUpDes" data-wow-delay="0.1s">
                                  <h1 class="promo__item-title">
                                  <?php the_field('promo__title'); ?>
                                </h1>
                                </div>
                                <div class="actions__wrap d-flex wow fadeInUpDes" data-wow-delay="0.2s">
                                    <a href="#order-modal"
                                       data-fancybox=""
                                       data-touch="false"
                                       data-modal-title="<?php the_field('modal__title-1', 'option'); ?>"
                                       data-modal-src="<?php the_field('modal__src-1', 'option'); ?>"
                                       class="link__custom">
                                      <?php the_field('promo__link'); ?>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="promo__nav-box d-flex align-items-center wow fadeInUpDes" data-wow-delay="0.1s" data-wow-offset="0">
            <div class="promo__nav-next d-flex align-items-center justify-content-center">
                <svg viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.24127e-07 9.7029L4.2029 5.5L5.66979e-08 1.2971L1.29705 -5.66961e-08L6.79706 5.5L1.29706 11L4.24127e-07 9.7029Z" fill="white"/>
                </svg>
            </div>
            <div class="promo__nav-info">
                <span class="nav__current">01</span> / <span class="nav__count">02</span>
            </div>
            <div class="promo__nav-prev d-flex align-items-center justify-content-center">
                <svg viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 9.7029L2.7971 5.5L7 1.2971L5.70295 -1.70088e-07L0.202945 5.5L5.70294 11L7 9.7029Z" fill="white"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="promo__bottom wow fadeInUpDes hidden-sm-down" data-wow-delay="0.2s" data-wow-offset="0">
        <div class="container d-flex justify-content-between align-items-center">
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
            <div class="promo__bottom-right d-flex align-items-center">
                <div class="promo__phone">
                    T:  <a href="tel:<?php echo formatPhone(get_field('ct__ph-1', 79)) ?>"><?php the_field('ct__ph-1', 79) ?></a>
                </div>
                <div class="phone__box d-flex align-items-center justify-content-center">
                    <div class="dots d-flex">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="phone__soc-links d-flex align-items-center justify-content-center">
                        <?php if(get_field('ct__whatsapp', 79)): ?>
                            <a href="https://api.whatsapp.com/send?phone=<?php the_field('ct__whatsapp', 79); ?>" target="_blank" class="soc__whatsapp d-flex align-items-center justify-content-center">
                                <svg viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8468 9.48154C15.335 5.37221 14.6122 2.77788 13.3086 1.60447L13.3093 1.60378C11.2052 -0.400087 4.09924 -0.696679 1.58196 1.69378C0.451499 2.86311 0.053316 4.57925 0.00967953 6.7038C-0.033957 8.82904 -0.0857754 12.8102 3.62401 13.8902H3.62742L3.62401 15.5395C3.62401 15.5395 3.59878 16.2077 4.0256 16.342C4.03871 16.3463 4.05162 16.3502 4.06437 16.3537C4.10382 16.3744 4.14878 16.3835 4.19518 16.3771C4.56098 16.4005 4.87546 16.0243 6.00043 14.6784L6.00044 14.6784L6.20273 14.4365L6.26133 14.377C8.79642 14.5955 10.7425 14.0919 10.9638 14.0184C11.0096 14.003 11.0744 13.986 11.1549 13.9649L11.1549 13.9648C11.9754 13.7493 14.4196 13.1071 14.8468 9.48154ZM7.39396 2.93925C7.13146 2.93925 7.13146 3.34834 7.39396 3.35175C9.43056 3.36743 11.1078 4.78698 11.1262 7.39086C11.1262 7.66563 11.5285 7.66222 11.5251 7.38745H11.5244C11.5026 4.58175 9.67124 2.95493 7.39396 2.93925ZM10.0721 6.9591C10.066 7.23046 10.4676 7.24342 10.471 6.96864C10.5044 5.42159 9.55052 4.14727 7.75802 4.01295C7.49551 3.99386 7.46824 4.40636 7.73006 4.42545C9.28461 4.54341 10.1035 5.60432 10.0721 6.9591ZM8.81899 8.83678C8.96081 8.64519 9.30377 8.52383 9.64059 8.71883C10.0947 8.98405 10.6729 9.39656 11.056 9.77019C11.2722 9.95224 11.3213 10.2304 11.1733 10.5243V10.5291C10.8399 11.1366 10.2154 11.8075 9.57309 11.5934L9.56695 11.5838C8.91513 11.395 7.37967 10.5768 6.40944 9.77838C4.70288 8.38292 3.96439 6.61431 3.56051 5.64705C3.47578 5.44413 3.40578 5.27649 3.34466 5.15495C3.13807 4.49017 3.78375 3.84449 4.37352 3.49949H4.37966C4.6633 3.34608 4.93603 3.39722 5.11739 3.62426C5.15982 3.68363 5.21232 3.74742 5.27488 3.82343C5.46663 4.0564 5.75287 4.40418 6.1333 5.09154C6.32217 5.43995 6.20489 5.7945 6.01944 5.94177L5.64853 6.24859C5.46035 6.40473 5.48557 6.69586 5.48557 6.69586C5.48557 6.69586 6.03512 8.84633 8.09013 9.38974C8.09013 9.38974 8.37172 9.41565 8.5224 9.22065L8.81899 8.83678ZM8.03663 5.53345C8.69186 5.57163 9.00959 5.9139 9.04368 6.61686C9.05595 6.89164 9.45482 6.87254 9.44254 6.59777C9.39891 5.68004 8.92027 5.16867 8.05845 5.12095C7.79595 5.10527 7.7714 5.51777 8.03663 5.53345Z" fill="white"/>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if(get_field('ct__telegram', 79)): ?>
                            <a href="<?php the_field('ct__telegram', 79); ?>" target="_blank" class="soc__telegram d-flex align-items-center justify-content-center">
                                <svg viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.09047 8.69128L6.20483 13.9975L8.95745 11.2341L13.677 15L17 0L0 7.10885L4.09047 8.69128ZM12.1422 4.30365L6.9388 9.06836L6.2907 11.5206L5.09344 8.5151L12.1422 4.30365Z" fill="white"/>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php if(get_field('ct__viber', 79)): ?>
                            <a href="viber://chat/?number=<?php the_field('ct__viber', 79); ?>" target="_blank" class="soc__viber d-flex align-items-center justify-content-center">
                                <svg viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.6148 2.38518C13.0767 0.847045 11.0317 0 8.8565 0C6.6813 0 4.63625 0.847045 3.09812 2.38518C1.55998 3.92331 0.712971 5.9683 0.712971 8.1435C0.712971 9.52139 1.06177 10.8754 1.72384 12.0748L0 17L4.92512 15.2762C6.12455 15.9382 7.47857 16.287 8.85647 16.287C11.0317 16.287 13.0767 15.44 14.6148 13.9018C16.1529 12.3637 17 10.3187 17 8.1435C17 5.96826 16.1529 3.92328 14.6148 2.38518ZM12.8823 11.1687L11.9422 12.1088C11.1634 12.8877 8.95352 11.9406 7.00646 9.99354C5.05936 8.04644 4.11231 5.83668 4.89115 5.05783L5.83127 4.11772C6.02597 3.92302 6.34166 3.92302 6.53637 4.11772L7.71153 5.29288C7.90623 5.48758 7.90623 5.80328 7.71153 5.99798L7.00642 6.70308C7.69037 8.14698 8.85301 9.30959 10.2969 9.99354L11.002 9.28844C11.1967 9.09374 11.5124 9.09374 11.7071 9.28844L12.8823 10.4636C13.077 10.6583 13.077 10.974 12.8823 11.1687Z" fill="white"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="scroll__down-wrap d-flex justify-content-center">
                <a href="#stat" class="scroll__down scroll"><?php _e('Вниз', 'mdt'); ?></a>
            </div>
        </div>
    </div>
</div>
<div id="stat" class="section sc__common sc__stat">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-12">
                <div class="sc__header">
                    <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        <?php the_field('inf__title'); ?>
                    </div>
                    <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.2s">
                      <?php the_field('inf__descr'); ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
                <div class="stat__list">
                  <?php $i= 1; while ( have_rows('inf__stat') ) : the_row();?>
                      <div class="stat__item d-flex align-items-center wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                          <div class="stat__item-value">
                              <?php the_sub_field('inf__stat-val'); ?>
                          </div>
                          <div class="stat__item-descr">
                            <?php the_sub_field('inf__stat-descr'); ?>
                          </div>
                          <div class="stat__item-icon d-flex align-items-center justify-content-end">
                              <img src="<?php the_sub_field('inf__stat-icon'); ?>">
                          </div>
                      </div>
                    <?php $i++; endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__work sc__common sc__gray">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="static__img wow fadeInUpDes" data-wow-delay="0.1s">
            <div class="img__wrap">
                <?php if(get_field('wk__img-mb')): ?>
                    <div class="img__bl hidden-sm-down">
                      <?php echo wp_get_attachment_image( get_field('wk__img'), 'f-prev', false, array('data-sizes' => 'auto' ) ); ?>
                    </div>
                    <div class="img__bl hidden-sm-up">
                      <?php echo wp_get_attachment_image( get_field('wk__img-mb'), 'f-prev-h', false, array('data-sizes' => 'auto' ) ); ?>
                    </div>
                <?php else: ?>
                    <div class="img__bl">
                      <?php echo wp_get_attachment_image( get_field('wk__img'), 'f-prev', false, array('data-sizes' => 'auto' ) ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="sc__content">
            <div class="sc__header">
                <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.2s">
                  <?php the_field('wk__title'); ?>
                </div>
            </div>
            <div class="toggle__list-box progress__list">
                <div class="toggle__list">
                    <?php $i= 1; while ( have_rows('wk__list') ) : the_row();?>
                      <div class="toggle__item wow fadeInUpDes <?php if($i === 1) echo 'active'; ?>" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                          <div class="toggle__item-header d-flex align-items-center">
                              <div class="toggle__item-title">
                                  <?php the_sub_field('wk__item-title'); ?>
                              </div>
                              <div class="toggle__arrow d-flex align-items-center justify-content-center">
                                  <svg viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M9.7029 0L5.5 4.2029L1.2971 0L0 1.29705L5.5 6.79705L11 1.29705L9.7029 0Z" fill="#404040"/>
                                  </svg>
                              </div>
                              <div class="status__bar"><span></span></div>
                          </div>
                          <div class="toggle__item-content" style="<?php if($i === 1) echo 'display: block;' ?>">
                              <div class="toggle__item-text">
                                <?php the_sub_field('wk__item-descr'); ?>
                              </div>
                          </div>
                      </div>
                    <?php $i++; endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__areas sc__common">
    <div class="container">
        <div class="areas__list col__list">
            <div class="row row__custom align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 col__custom col__item">
                    <div class="sc__header">
                        <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                          <?php the_field('ar__title'); ?>
                        </div>
                        <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.2s">
                          <?php the_field('ar__descr'); ?>
                        </div>
                    </div>
                </div>
              <?php
              remove_all_filters('posts_orderby');
              $args = array(
                'post_type' => 'use',
                'publish' => true,
                'orderby' => 'date',
                'posts_per_page' => -1,
                'order' => 'DESC'
              );
              $posts = new WP_Query($args );
              if ($posts) :

              $i = 1; while ( $posts->have_posts() ) :
              $posts->the_post();
              $id=$posts->ID;
              setup_postdata($post); ?>
                  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.1s">
                      <a href="<?php  the_permalink(); ?>" class="card__bl-1">
                          <div class="img__wrap">
                              <div class="img__bl">
                                  <img src="<?php echo get_field('us__prev')['sizes']['us-prev']; ?>">
                              </div>
                          </div>
                          <div class="card__header d-flex align-items-center">
                              <div class="card__title">
                                  <?php the_field('us__group-name'); ?>
                              </div>
                          </div>
                          <div class="redirect__decor d-flex align-items-center justify-content-center">
                              <svg viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <rect x="6.28906" width="11" height="1.5" transform="rotate(90 6.28906 0)" fill="#404040"/>
                                  <rect x="11.0391" y="6.25" width="11" height="1.5" transform="rotate(-180 11.0391 6.25)" fill="#404040"/>
                              </svg>
                              <div class="arrow__right">
                                  <svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M0.101562 2.19566L4.30446 6.39856L0.101562 10.6015L1.39862 11.8986L6.89862 6.39856L1.39862 0.89856L0.101562 2.19566Z" fill="white"/>
                                  </svg>
                              </div>
                          </div>
                      </a>
                  </div>
                <?php $i++; endwhile; ?>
              <?php endif; wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="pager__wrap d-flex justify-content-center wow fadeInUpDes hidden-sm-up" data-wow-delay="0.2s">
            <a href="<?php echo get_permalink(lang_page_original_id(59)) ?>" class="link__custom link__custom-dark">
              <?php _e('Більше', 'mdt'); ?>
            </a>
        </div>
    </div>
</div>
<div class="section sc__assortment sc__floor sc__common sc__gray">
    <div class="container">
        <div class="sc__header">
            <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
              <?php _e('Асортимент', 'mdt'); ?>
            </div>
        </div>
        <?php $cat_chs = mdt_top_level_categories(); ?>
        <div class="floor__list col__list">
            <div class="row row__custom">
              <?php $k = 1; foreach( (array) $cat_chs as $ch ) : ?>
                  <?php if($k < 9): ?>
                      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $k; ?>s">
                          <a href="<?php the_permalink($ch); ?>" class="card__bl-2">
                              <div class="img__wrap">
                                  <div class="img__bl">
                                      <img src="<?php echo get_the_post_thumbnail_url( $ch, 'pr-prev'); ?>">
                                  </div>
                              </div>
                              <div class="card__header d-flex align-items-center">
                                  <div class="card__title">
                                    <?php echo get_the_title($ch); ?>
                                  </div>
                              </div>
                              <div class="card__descr">
                                <?php echo get_field('cat__subtitle', $ch); ?>
                              </div>
                              <div class="redirect__decor d-flex align-items-center justify-content-center">
                                  <svg viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <rect x="6.28906" width="11" height="1.5" transform="rotate(90 6.28906 0)" fill="#404040"/>
                                      <rect x="11.0391" y="6.25" width="11" height="1.5" transform="rotate(-180 11.0391 6.25)" fill="#404040"/>
                                  </svg>
                                  <div class="arrow__right">
                                      <svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M0.101562 2.19566L4.30446 6.39856L0.101562 10.6015L1.39862 11.8986L6.89862 6.39856L1.39862 0.89856L0.101562 2.19566Z" fill="white"/>
                                      </svg>
                                  </div>
                              </div>
                          </a>
                      </div>
                  <?php endif; ?>
                <?php $k ++; endforeach; ?>
            </div>
        </div>

    </div>
</div>
<div class="section sc__others sc__common">
    <div class="container">
        <div class="sc__box sc__box-partners">
            <div class="sc__header d-flex align-items-center justify-content-between">
                <div class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                    <?php the_field('part__title'); ?>
                </div>
                <div class="nav__box d-flex wow fadeInUpDes" data-wow-delay="0.2s">
                    <div class="nav__slide nav__prev d-flex align-items-center justify-content-center">
                        <svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.89844 9.80447L2.69554 5.60156L6.89844 1.39866L5.60138 0.101563L0.101383 5.60156L5.60138 11.1016L6.89844 9.80447Z" fill="#343434"/>
                        </svg>
                    </div>
                    <div class="nav__slide nav__next d-flex align-items-center justify-content-center">
                        <svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.101562 2.19553L4.30446 6.39844L0.101562 10.6013L1.39862 11.8984L6.89862 6.39844L1.39862 0.898437L0.101562 2.19553Z" fill="#343434"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="partners__slider-box">
                <div class="partners__slider-row row__custom">
                    <div class="partners__slider">
                      <?php
                      remove_all_filters('posts_orderby');
                      $args = array(
                        'post_type' => 'suppliers',
                        'publish' => true,
                        'orderby' => 'date',
                        'posts_per_page' => -1,
                        'order' => 'DESC'
                      );
                      $posts = new WP_Query($args );
                      if ($posts) :

                        $i = 1; while ( $posts->have_posts() ) :
                        $posts->the_post();
                        $id=$posts->ID;
                        setup_postdata($post); ?>
                          <div class="partners__item col__custom wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * (($i - 1) % 4 + 1); ?>s">
                              <a href="<?php the_permalink(); ?>" class="partners__item-bl d-flex align-items-center justify-content-center">
                                  <div class="partners__item-img">
                                      <img src="<?php the_field('sp__logo-dark') ?>" width="100">
                                  </div>
                                  <div class="redirect__info d-flex align-items-center justify-content-center">
                                      <?php _e('Детальніше', 'mdt'); ?>
                                  </div>
                              </a>
                          </div>
                        <?php $i++; endwhile; ?>
                      <?php endif; wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__action sc__common sc__gray">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="sc__content">
                    <div class="action__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                      <?php the_field('op__f-title', 'option') ?>
                    </div>
                    <div class="actions__wrap d-flex wow fadeInUpDes" data-wow-delay="0.2s">
                        <a href="#order-modal"
                           data-fancybox=""
                           data-touch="false"
                           data-modal-title="<?php the_field('modal__title-3', 'option'); ?>"
                           data-modal-src="<?php the_field('modal__src-3', 'option'); ?>" class="link__custom link__custom-dark">
                          <?php the_field('op__f-link', 'option') ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="static__img wow fadeInUpDes" data-wow-delay="0.3s">
                    <div class="img__wrap">
                        <div class="img__bl hidden-sm-down">
                          <?php echo wp_get_attachment_image( get_field('op__f-img', 'option'), 'f-prev', false, array('data-sizes' => 'auto' ) ); ?>
                        </div>
                        <div class="img__bl hidden-sm-up">
                          <?php echo wp_get_attachment_image( get_field('op__f-img-mb', 'option'), 'f-prev-h', false, array('data-sizes' => 'auto' ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__common sc__news">
    <div class="container">
        <div class="sc__header">
            <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
              <?php _e('Останні новини', 'mdt'); ?>
            </div>
        </div>
        <div class="news__list">
            <div class="row row__custom">
              <?php

              remove_all_filters('posts_orderby');
              $args = array(
                'post_type' => 'post',
                'publish' => true,
                'orderby' => 'date',
                'posts_per_page' => 4,
                'order' => 'DESC'
              );
              $posts = new WP_Query($args );
              if ($posts) :

                $i = 1; while ( $posts->have_posts() ) :
                $posts->the_post();
                $id=$posts->ID;
                setup_postdata($post); ?>
                  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                      <a href="<?php the_permalink(); ?>" class="news__item-block">
                          <div class="news__item-img">
                              <div class="img__wrap">
                                  <div class="img__bl">
                                      <img src="<?php echo get_field('n__prev')['sizes']['n-prev']; ?>">
                                  </div>
                              </div>
                          </div>
                          <div class="news__item-content">
                              <div class="news__item-title">
                                  <span><?php echo get_the_title(); ?></span>
                              </div>
                              <div class="news__item-date">
                                <?php the_time('d.m.Y'); ?>
                              </div>
                          </div>
                      </a>
                  </div>
                <?php if($i === 4) $i = 1; else $i++; endwhile; ?>
              <?php endif; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>
<?php if(get_field('seo__title') && get_field('seo__text')): ?>
  <!--   <div class="section sc__gray sc__seo">
        <div class="container">
            <div class="sc__header">
                <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                  <?php the_field('seo__title') ?>
                </div>
            </div>
            <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.2s">
              <?php the_field('seo__text') ?>
            </div>
        </div>
    </div> -->
<?php endif; ?>

<?php get_footer(); ?>
