<?php /*Template Name: News page*/ ?>
<?php get_header(); ?>

<div class="section sc__inside">
    <div class="container">
        <div class="sc__header">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        <h1>
                        <?php the_title(); ?>
                    </h1>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 wow fadeInUpDes" data-wow-delay="0.3s">
                    <div class="sc__descr">
                        <?php the_field('p__descr'); ?>
                    </div>
                </div>
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
                'posts_per_page' => -1,
                'order' => 'DESC'
              );
              $posts = new WP_Query($args );
              if ($posts) :

              $i = 1; while ( $posts->have_posts() ) :
              $posts->the_post();
              $id=$posts->ID;
              setup_postdata($post); ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
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
                                <span><?php echo get_the_title() ?></span>
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

<?php get_footer(); ?>
