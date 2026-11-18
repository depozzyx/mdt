<?php
/**
 * Call-to-action strip on category pages. Uses the site-wide texts from the options page unless
 * the category has its own (lp__cta-* fields, see mdt_landing_field_group()); single-category.php
 * then moves it below the FAQ. A category's own CTA opens the consultation form (modal 1) rather
 * than the samples form (modal 3).
 */
$lp_cta  = get_field( 'lp__cta-title' );
$modal_n = $lp_cta ? 1 : 3;
?>
    <div class="section sc__action sc__common sc__gray">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="sc__content">
                        <div class="action__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                          <?php echo $lp_cta ? esc_html( $lp_cta ) : get_field( 'op__f-title', 'option' ); ?>
                        </div>
                        <?php if ( $lp_cta && get_field( 'lp__cta-text' ) ): ?>
                            <div class="sc__descr lp__cta-text wow fadeInUpDes" data-wow-delay="0.15s">
                              <?php echo esc_html( get_field( 'lp__cta-text' ) ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="actions__wrap d-flex wow fadeInUpDes" data-wow-delay="0.2s">
                            <a href="#order-modal"
                               data-fancybox=""
                               data-touch="false"
                               data-modal-title="<?php the_field( 'modal__title-' . $modal_n, 'option' ); ?>"
                               data-modal-src="<?php the_field( 'modal__src-' . $modal_n, 'option' ); ?>" class="link__custom link__custom-dark">
                              <?php echo ( $lp_cta && get_field( 'lp__cta-btn' ) ) ? esc_html( get_field( 'lp__cta-btn' ) ) : get_field( 'op__f-link', 'option' ); ?>
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
