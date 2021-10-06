<?php /*Template Name: Reviews page*/ ?>
<?php get_header(); ?>

<div class="section sc__inside sc__inside-top sc__reviews">
    <div class="breadcrumbs wow fadeInUpDes" data-wow-delay="0.1s">
        <ul>
            <li>
                <a href="/">Главная</a>  /
            </li>
            <li>
                <?php the_title(); ?>
            </li>
        </ul>
    </div>
    <div class="container">
        <div class="content__wrap">
            <div class="row row__custom justify-content-end">
                <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 col__custom">
                    <div class="content__box">
                        <div class="sc__header">
                            <div class="sc__title sc__title-small seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                                <?php the_field('rv__page-title') ?>
                            </div>
                        </div>
                        <div class="reviews__list">
                          <?php
                          remove_all_filters('posts_orderby');
                          $args = array(
                            'post_type' => 'reviews',
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
                              <div class="reviews__item d-flex align-items-start">
                                  <div class="reviews__item-logo d-flex align-items-center justify-content-center wow fadeInUpDes" data-wow-delay="0.1s">
                                      <img src="<?php echo get_field('rv__item-logo') ?>" width="150">
                                  </div>
                                  <div class="reviews__item-content wow fadeInUpDes" data-wow-delay="0.3s">
                                      <div class="rev__decor">
                                          <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                              <path d="M1.04907e-06 0.250001L0 12.25L6.5 6.25L6.5 0.250002L1.04907e-06 0.250001Z" fill="#DA1F1F"/>
                                              <path d="M9.5 0.250001L9.5 12.25L16 6.25L16 0.250002L9.5 0.250001Z" fill="#DA1F1F"/>
                                          </svg>
                                      </div>
                                      <div class="reviews__item-text">
                                        <?php the_field('rv__item-text') ?>
                                      </div>
                                      <div class="rev__sep"></div>
                                      <div class="reviews__item-name">
                                          <?php the_field('rv__item-name') ?>
                                      </div>
                                      <div class="reviews__item-company">
                                        <?php the_field('rv__item-company') ?>
                                      </div>
                                  </div>
                              </div>
                            <?php $i++; endwhile; ?>
                          <?php endif; wp_reset_postdata(); ?>
                        </div>
                        <!--<div class="pager__wrap d-flex justify-content-center wow fadeInUpDes" data-wow-delay="0.2s">
                            <a href="#" class="link__custom link__custom-dark">
                                Показать еще
                            </a>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(get_field('seo__title') && get_field('seo__text')): ?>
<!--     <div class="section sc__seo sc__gray">
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
