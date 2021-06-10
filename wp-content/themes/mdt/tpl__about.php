<?php /*Template Name: About us template*/ ?>
<?php get_header(); ?>

<div class="section sc__promo-inside" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full'); ?>)">
    <div class="container d-flex align-items-center">
        <div class="promo__content-wrap">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-12 col-12">
                    <div class="promo__content">
                        <div class="promo__item-title wow fadeInUpDes" data-wow-delay="0.1s">
                            <h1 class="promo__item-title">
                            <?php the_field('ab__pr-title') ?>
                        </h1>
                        </div>
                        <div class="actions__wrap d-flex wow fadeInUpDes" data-wow-delay="0.2s">
                            <a href="<?php the_permalink(lang_page_original_id(79)) ?>" class="link__custom">
                               <?php echo get_the_title(lang_page_original_id(79)) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="inside__nav-box wow fadeInUpDes" data-wow-delay="0.1s">
            <div class="inside__nav">
                <ul class="d-flex justify-content-between align-items-center">
                    <?php $i= 1; while ( have_rows('ab__menu') ) : the_row();?>
                        <?php if($i !== 1): ?>
                            <div class="nav__sep"></div>
                        <?php endif; ?>
                        <li>
                            <a href="#<?php the_sub_field('ab__menu-anchor'); ?>" class="scroll"><?php the_sub_field('ab__menu-name'); ?></a>
                        </li>
                    <?php $i++; endwhile; ?>
                </ul>
            </div>
        </div>

    </div>
</div>
<div class="section sc__about-info sc__gray" id="about">
    <div class="container">
        <div class="about__info-wrap">
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                    <div class="about__info-left">
                        <div class="about__info-left-bl">
                            <div class="sc__subtitle wow fadeInUpDes" data-wow-delay="0.1s">
                                <?php the_field('ab__in-title') ?>
                            </div>
                            <div class="info__ab-item wow fadeInUpDes" data-wow-delay="0.2s">
                                <div class="info__item-value info__ab-item-value-main">
                                  <?php the_field('ab__in-text-1') ?>
                                </div>
                                <div class="info__ab-item-descr">
                                  <?php the_field('ab__in-text-2') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                    <div class="about__info-right">
                        <div class="about__info-right-bl">
                            <div class="info__ab-list">
                                <?php $i= 1; while ( have_rows('ab__stat') ) : the_row();?>
                                    <div class="info__ab-item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                        <div class="info__ab-item-value">
                                            <?php the_sub_field('ab__stat-value') ?>
                                        </div>
                                        <div class="info__ab-item-descr">
                                          <?php the_sub_field('ab__stat-descr') ?>
                                        </div>
                                    </div>
                                <?php $i++; endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__about-services">
    <div class="container">
        <div class="sc__box sc__box-slogan">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 col__custom">
                    <div class="static__img wow fadeInUpDes" data-wow-delay="0.1s">
                        <div class="img__wrap">
                            <div class="img__bl">
                              <?php echo wp_get_attachment_image( get_field('ab__in-photo'), 'a-1', false, array('data-sizes' => 'auto' ) ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 col__custom">
                    <div class="reviews__item-content wow fadeInUpDes" data-wow-delay="0.3s">
                        <div class="rev__decor">
                            <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.04907e-06 0.250001L0 12.25L6.5 6.25L6.5 0.250002L1.04907e-06 0.250001Z" fill="#DA1F1F"/>
                                <path d="M9.5 0.250001L9.5 12.25L16 6.25L16 0.250002L9.5 0.250001Z" fill="#DA1F1F"/>
                            </svg>
                        </div>
                        <div class="reviews__item-text">
                            <?php the_field('ab__in-quote') ?>
                        </div>
                        <div class="rev__sep"></div>
                        <div class="reviews__item-name">
                          <?php the_field('ab__in-quote-author') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sc__box" id="services">
            <div class="sc__header">
                <div class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                    <?php the_field('ab__sv-title') ?>
                </div>
            </div>
            <?php $ab_list = get_field('typ__list'); ?>
            <div class="info__list">
                <div class="row row__custom">
                    <?php $i = 1; foreach( (array) $ab_list as $ab_id ) : ?>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 col__custom info__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                            <a href="<?php the_permalink($ab_id); ?>" class="info__item-bl d-flex align-items-center">
                                <div class="info__item-img">
                                    <img src="<?php echo get_field('typ__img-prev', $ab_id)['sizes']['t-1'] ?>">
                                </div>
                                <div class="info__item-content">
                                    <div class="info__item-title">
                                      <?php echo  get_the_title($ab_id) ?>
                                    </div>
                                    <div class="info__item-descr">
                                      <?php the_field('typ__descr', $ab_id) ?>
                                    </div>
                                </div>
                                <div class="redirect__decor d-flex align-items-center justify-content-center">
                                    <svg viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="6.28906" width="11" height="1.5" transform="rotate(90 6.28906 0)" fill="#404040"></rect>
                                        <rect x="11.0391" y="6.25" width="11" height="1.5" transform="rotate(-180 11.0391 6.25)" fill="#404040"></rect>
                                    </svg>
                                    <div class="arrow__right">
                                        <svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.101562 2.19566L4.30446 6.39856L0.101562 10.6015L1.39862 11.8986L6.89862 6.39856L1.39862 0.89856L0.101562 2.19566Z" fill="white"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__work sc__common sc__gray" id="why">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="static__img wow fadeInUpDes" data-wow-delay="0.1s">
            <div class="img__wrap">
                <div class="img__bl hidden-sm-down">
                  <?php echo wp_get_attachment_image( get_field('ab__wh-img'), 'f-prev', false, array('data-sizes' => 'auto' ) ); ?>
                </div>
                <div class="img__bl hidden-sm-up">
                  <?php echo wp_get_attachment_image( get_field('ab__wh-img-mb'), 'f-prev-h', false, array('data-sizes' => 'auto' ) ); ?>
                </div>
            </div>
        </div>
        <div class="sc__content">
            <div class="sc__header">
                <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.2s">
                    <?php the_field('ab__wh-title'); ?>
                </div>
            </div>
            <div class="toggle__list-box">
                <div class="toggle__list progress__list">
                    <?php $i = 1; while ( have_rows('ab__wh-list') ) : the_row();?>
                        <div class="toggle__item wow fadeInUpDes <?php if($i === 1) echo 'active'; ?>" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                            <div class="toggle__item-header d-flex align-items-center">
                                <div class="toggle__item-title">
                                    <?php the_sub_field('ab__wh-list-name'); ?>
                                </div>
                                <div class="toggle__arrow d-flex align-items-center justify-content-center">
                                    <svg viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.7029 0L5.5 4.2029L1.2971 0L0 1.29705L5.5 6.79705L11 1.29705L9.7029 0Z" fill="#404040"/>
                                    </svg>
                                </div>
                                <div class="status__bar"></div>
                            </div>
                            <div class="toggle__item-content" style="<?php if($i === 1) echo 'display: block;'; ?>">
                                <div class="toggle__item-text">
                                  <?php the_sub_field('ab__wh-list-descr'); ?>
                                </div>
                            </div>
                        </div>
                    <?php $i++; endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__chess sc__common">
    <div class="container">
        <div class="chess__list">
            <?php $i= 1; $size = 'f-prev'; while ( have_rows('ab__text-list') ) : the_row();?>
                <?php if($i % 2 === 0 ) $size = 'a-2'; else $size = 'f-prev'; ?>
                <div class="chess__item">
                    <div class="row m-0 align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 p-0">
                            <div class="chess__item-content">
                                <div class="post__text wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                    <?php the_sub_field('ab__text-content') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 p-0">
                            <div class="static__img wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                <div class="img__wrap">
                                    <div class="img__bl">
                                      <?php echo wp_get_attachment_image( get_sub_field('ab__text-img'), $size, false, array('data-sizes' => 'auto' ) ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $i++; endwhile; ?>
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
                        <div class="img__bl">
                          <?php echo wp_get_attachment_image( get_field('op__f-img', 'option'), 'f-prev', false, array('data-sizes' => 'auto' ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__partners sc__box-partners" id="partners">
    <div class="container">
        <div class="sc__header d-flex align-items-center justify-content-between">
            <div class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                <?php _e('Наші партнери', 'mdt'); ?>
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
                      <div class="partners__item col__custom wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                          <a href="<?php the_permalink(); ?>" class="partners__item-bl d-flex align-items-center justify-content-center">
                              <div class="partners__item-img">
                                  <img src="<?php the_field('sp__logo-dark') ?>" width="100">
                              </div>
                              <div class="redirect__info d-flex align-items-center justify-content-center">
                                  Подробнее
                              </div>
                          </a>
                      </div>
                    <?php $i++; endwhile; ?>
                  <?php endif; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
        <div class="pager__wrap d-flex justify-content-center wow fadeInUpDes" data-wow-delay="0.2s">
            <a href="<?php the_permalink(lang_page_original_id(42)) ?>" class="link__custom link__custom-dark">
                <?php _e('Відгуки наших партнерів', 'mdt'); ?>
            </a>
        </div>
    </div>
</div>
<div class="section sc__vacancy sc__common sc__gray" id="vacancy">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="sc__header">
                    <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        <?php the_field('ab__vac-title') ?>
                    </div>
                    <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.2s">
                      <?php the_field('ab__vac-subtitle') ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="sc__content">
                    <div class="cat__toggle-box">
                        <div class="cat__toggle-list">
                          <?php $i = 1; while ( have_rows('ab__vac-list') ) : the_row();?>
                              <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                  <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                      <div class="cat__toggle-item-left d-flex align-items-center">
                                          <div class="d-flex align-items-start">
                                              <div class="cat__toggle-item-numb">
                                                <?php echo sprintf("%02d", $i) ?>.
                                              </div>
                                              <div class="cat__toggle-item-title">
                                                <?php the_sub_field('ab__vac-item-name') ?>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="arrow__toggle d-flex align-items-center justify-content-center">
                                          <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <rect x="6.25" width="11" height="1.5" transform="rotate(90 6.25 0)" fill="#343434"/>
                                              <rect x="11" y="6.25" width="11" height="1.5" transform="rotate(-180 11 6.25)" fill="#343434"/>
                                          </svg>
                                      </div>
                                  </div>
                                  <div class="cat__toggle-content">
                                      <div class="post__text">
                                        <?php the_sub_field('ab__vac-descr') ?>
                                      </div>
                                  </div>
                              </div>
                            <?php $i++; endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(get_field('seo__title') && get_field('seo__text')): ?>
    <div class="section sc__seo">
        <div class="container">
            <div class="sc__header">
                <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                    <h2>
                      <?php the_field('seo__title') ?>
                    </h2>
                </div>
            </div>
            <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.2s">
              <?php the_field('seo__text') ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
