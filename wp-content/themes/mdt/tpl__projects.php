<?php /*Template Name: Projects page*/ ?>
<?php get_header(); ?>

<div class="section sc__inside sc__projects">
    <div class="container">
        <div class="row row__custom">
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 col__custom">
                <div class="sc__header">
                    <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                      <h1>
                        <?php the_title(); ?>
                      </h1>
                    </div>
                </div>
                <div class="filter__list wow fadeInUpDes hidden-sm-down" data-wow-delay="0.3s">
                    <ul>
                        <li class="<?php if(!isset($_GET['u'])) echo 'active'; ?>">
                            <a href="<?php echo get_permalink(lang_page_original_id(59)) ?>"><?php _e('Усі', 'mdt'); ?></a>
                        </li>
                      <?php

                      remove_all_filters('posts_orderby');
                      $active_span = '';
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
                            <li class="<?php if(isset($_GET['u']) && intval($_GET['u']) === intval($id)) {echo 'active'; $active_span = get_field('us__group-name');}  ?>">
                                <a href="<?php echo get_permalink(lang_page_original_id(59)) ?>?u=<?php echo $id; ?>"><?php the_field('us__group-name'); ?></a>
                            </li>
                        <?php $i++; endwhile; ?>
                      <?php endif; wp_reset_postdata(); ?>
                    </ul>
                </div>
                <div class="filter__select hidden-sm-up">
                    <div class="filter__select-input d-flex align-items-center justify-content-between">
                        <span><?php if(!isset($_GET['u'])) echo 'Усі'; else echo $active_span; ?></span>
                        <i class="icon__select">
                            <svg viewBox="0 0 9 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.5 5.14258L-3.71752e-07 0.890227L0.984506 -0.000279203L4.5 3.32175L8.01549 -0.000279817L9 0.890226L4.5 5.14258Z" fill="#343434"/>
                            </svg>
                        </i>
                    </div>
                    <div class="filter__dropdown">
                        <div class="filter__dropdown-scroll">
                            <div class="filter__dropdown-item <?php if(!isset($_GET['u'])) echo 'active'; ?>">
                                <a href="<?php echo get_permalink(lang_page_original_id(59)) ?>"><?php _e('Усі', 'mdt'); ?></a>
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
                              <div class="filter__dropdown-item <?php if(isset($_GET['u']) && intval($_GET['u']) === intval($id)) echo 'active'; ?>">
                                  <a href="<?php echo get_permalink(lang_page_original_id(59)) ?>?u=<?php echo $id; ?>"><?php the_field('us__group-name'); ?></a>
                              </div>
                            <?php $i++; endwhile; ?>
                          <?php endif; wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 col__custom">
                <div class="projects__list col__list">
                    <?php 
                        
                        if(isset($_GET['u']) && intval($_GET['u'])) {
                            // echo do_shortcode('[cpt_ajax_load_more button_text="Показати ще" post_type="projects" init_load="9" template="projects-loop" posts_per_page="9"]');
                            echo do_shortcode('[cpt_ajax_load_more button_text="Показати ще" tag="'.$_GET['u'].'" post_type="projects" init_load="9" template="projects-loop" posts_per_page="9"]');
                        } else {
                            echo do_shortcode('[cpt_ajax_load_more button_text="Показати ще" post_type="projects" init_load="9" template="projects-loop" posts_per_page="9"]');
                        }
                        
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(get_field('seo__title') && get_field('seo__text')): ?>
 <!--    <div class="section sc__seo sc__gray">
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
