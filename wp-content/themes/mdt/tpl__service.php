<?php /*Template Name: Service page*/ ?>
<?php get_header(); ?>

<div class="section sc__service-cat sc__gray">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="sc__header">
                    <div class="sc__title sc__title-small seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        <?php the_title(); ?>
                    </div>
                    <div class="sc__descr wow fadeInUpDes" data-wow-delay="0.3s">
                        <?php the_field('serv__descr') ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="sc__content">
                    <div class="cat__toggle-box">
                        <div class="cat__toggle-list">
                          <?php $i= 1; while ( have_rows('srv__groups') ) : the_row();?>
                              <?php if(get_sub_field('srv__group-type') === true): ?>
                                  <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="<?php echo $i * 0.1; ?>s">
                                      <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                          <div class="cat__toggle-item-left d-flex align-items-center">
                                              <div class="cat__toggle-item-numb">
                                                <?php echo sprintf("%02d", $i)?>.
                                              </div>
                                              <div class="cat__toggle-item-title">
                                                <?php the_sub_field('srv__group-name'); ?>
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
                                          <div class="subcat__toggle-list">
                                             <?php $j= 1; while ( have_rows('srv__group-items') ) : the_row();?>
                                                 <a target="<?php if(get_sub_field('srv__item-pdf')) echo '_blank'; ?>" href="<?php if(get_sub_field('srv__item-pdf'))echo get_sub_field('srv__item-pdf'); else echo get_sub_field('srv__item-right'); ?>" class="subcat__toggle-item d-flex align-items-center justify-content-between">
                                                     <div class="subcat__toggle-item-left d-flex align-items-center">
                                                         <div class="pr__prev-small">
                                                             <img src="<?php echo get_the_post_thumbnail_url( get_sub_field('srv__item-left'), 't-2'); ?>">
                                                         </div>
                                                         <div class="subcat__toggle-item-name">
                                                           <?php _e('Колекція', 'mdt'); ?> <?php echo get_the_title(get_sub_field('srv__item-left')) ?>
                                                         </div>
                                                     </div>
                                                     <div class="link__detail-wrap d-flex justify-content-end">
                                                         <div class="link__detail d-flex align-items-center">
                                                             <span> <?php _e('Детальніше', 'mdt'); ?></span>
                                                             <i class="icon__detail">
                                                                 <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                     <path d="M-4.24127e-07 1.2971L4.2029 5.5L-5.66979e-08 9.7029L1.29705 11L6.79705 5.5L1.29705 -5.66961e-08L-4.24127e-07 1.2971Z" fill="#404040"/>
                                                                 </svg>
                                                             </i>
                                                         </div>
                                                     </div>
                                                 </a>
                                              <?php $j++; endwhile; ?>
                                          </div>
                                      </div>
                                  </div>
                              <?php else: ?>
                                  <a target="<?php if(get_sub_field('srv__group-pdf')) echo '_blank'; ?>" href="<?php if(get_sub_field('srv__group-pdf'))echo get_sub_field('srv__group-pdf'); else echo get_sub_field('srv__group-link'); ?>" class="cat__toggle-item wow fadeInUpDes" data-wow-delay="<?php echo $i * 0.1; ?>s">
                                      <div class="cat__toggle-item-header d-flex align-items-center justify-content-between">
                                          <div class="cat__toggle-item-left d-flex align-items-center">
                                              <div class="d-flex align-items-start">
                                                  <div class="cat__toggle-item-numb">
                                                    <?php echo sprintf("%02d", $i)?>.
                                                  </div>
                                                  <div class="cat__toggle-item-title">
                                                    <?php the_sub_field('srv__group-name'); ?>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="arrow__toggle d-flex align-items-center justify-content-center">
                                              <svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                  <path d="M-4.24127e-07 1.2971L4.2029 5.5L-5.66979e-08 9.7029L1.29705 11L6.79705 5.5L1.29705 -5.66961e-08L-4.24127e-07 1.2971Z" fill="#404040"/>
                                              </svg>
                                          </div>
                                      </div>
                                  </a>
                              <?php endif; ?>
                            <?php $i++; endwhile; ?>
                        </div>
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
