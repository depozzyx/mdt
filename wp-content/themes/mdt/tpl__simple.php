<?php /*Template Name: Simple template*/ ?>
<?php get_header(); ?>

<div class="section sc__inside sc__inside-top sc__post-header">
    <div class="breadcrumbs wow fadeInUpDes hidden-lg-down" data-wow-delay="0.1s">
        <ul>
            <li>
                <a href="<?php echo get_permalink(lang_page_original_id(9)) ?>"><?php echo get_the_title(lang_page_original_id(9)) ?></a>  /
            </li>
            <li>
               <?php the_title(); ?>
            </li>
        </ul>
    </div>
    <div class="container">
        <div class="breadcrumbs wow fadeInUpDes hidden-lg-up" data-wow-delay="0.1s">
            <ul>
                <li>
                    <a href="<?php echo get_permalink(lang_page_original_id(9)) ?>"><?php echo get_the_title(lang_page_original_id(9)) ?></a>  /
                </li>
                <li>
                  <?php the_title(); ?>
                </li>
            </ul>
        </div>
        <div class="post__header">
            <div class="content__wrap">
                <div class="row row__custom justify-content-end">
                    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12 col__custom">
                        <div class="content__box">
                            <div class="post__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                                <h1>
                                  <?php the_title(); ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__post-content">
    <div class="post__box">
        <div class="container">
            <div class="content__wrap">
                <div class="row row__custom justify-content-end">
                    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 col__custom">
                        <div class="content__box">
                            <div class="post__text wow fadeInUpDes" data-wow-delay="0.2s">
                                <?php the_content(); ?>
                            </div>
                        </div>
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

<?php get_footer(); ?>
