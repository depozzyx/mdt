<?php
/**
 * Section landing blocks for a catalogue category: subsection tiles, usage table, brands and
 * projects, plus the intro text above them. Rendered inside .sc__post-content, ahead of the
 * bl__list rows, so the theme's spacing rules between grey and white post__box sections apply. Fields: mdt_landing_field_group() in functions.php. Each block renders only when it
 * has content. Relationship and post-object ids resolve to the current language through
 * mdt_translate_related_posts().
 */

$lp_tiles  = get_field( 'lp__tiles' );
$lp_use    = get_field( 'lp__use' );
$lp_brands = get_field( 'lp__brands' );
$lp_proj   = get_field( 'lp__proj' );

$lp_arrow = '<div class="redirect__decor d-flex align-items-center justify-content-center">'
	. '<svg viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6.28906" width="11" height="1.5" transform="rotate(90 6.28906 0)" fill="#404040"/><rect x="11.0391" y="6.25" width="11" height="1.5" transform="rotate(-180 11.0391 6.25)" fill="#404040"/></svg>'
	. '<div class="arrow__right"><svg viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.101562 2.19566L4.30446 6.39856L0.101562 10.6015L1.39862 11.8986L6.89862 6.39856L1.39862 0.89856L0.101562 2.19566Z" fill="white"/></svg></div>'
	. '</div>';
?>

<?php if ( get_field( 'lp__intro' ) ): ?>
    <div class="post__box lp__intro">
        <div class="container">
            <div class="content__wrap">
                <div class="row row__custom justify-content-end">
                    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 col__custom">
                        <div class="content__box">
                            <div class="post__text wow fadeInUpDes" data-wow-delay="0.1s">
                              <?php the_field( 'lp__intro' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $lp_products = get_field( 'lp__products' ); ?>
<?php if ( $lp_products ): ?>
    <div class="post__box section sc__gray lp__products">
        <div class="container">
            <div class="sc__header">
                <h2 class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                  <?php echo get_field( 'lp__products-title' ) ? esc_html( get_field( 'lp__products-title' ) ) : __( 'Колекції', 'mdt' ); ?>
                </h2>
            </div>
            <div id="rep-box-lp-products" class="collection__list col__list">
                <div class="row row__custom align-items-center">
                  <?php $j = 1; foreach ( $lp_products as $item ): ?>
                    <?php $price = get_field( 'p__price', $item ); ?>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom col__item fadeInUpDes <?php echo $j > 8 ? 'animated hidden ' : 'wow'; ?>" data-wow-delay="<?php echo 0.1 * $j; ?>s">
                          <a href="<?php the_permalink( $item ); ?>" class="card__bl-3">
                              <div class="img__wrap">
                                  <div class="img__bl">
                                      <img src="<?php echo esc_url( get_the_post_thumbnail_url( $item, 'pr-prev' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $item ) ); ?>" title="<?php echo esc_attr( get_the_title( $item ) ); ?>">
                                  </div>
                              </div>
                              <div class="card__header">
                                  <div class="card__title"><?php echo esc_html( get_the_title( $item ) ); ?></div>
                                  <?php if ( $price ): ?>
                                      <div class="card__info"><?php _e( 'Ціна від', 'mdt' ); ?> <?php echo esc_html( $price ); ?> <span><?php _e( 'грн. / м²', 'mdt' ); ?></span></div>
                                  <?php endif; ?>
                              </div>
                              <?php echo $lp_arrow; ?>
                          </a>
                      </div>
                  <?php $j++; endforeach; ?>
                </div>
            </div>
            <?php if ( count( $lp_products ) > 8 ): ?>
                <div class="pager__wrap d-flex justify-content-center wow fadeInUpDes" data-wow-delay="0.2s">
                    <a data-load="rep-box-lp-products" href="#" class="link__custom link__custom-dark load-items-js"><?php _e( 'Показати ще', 'mdt' ); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ( $lp_tiles ): ?>
    <div class="post__box section lp__tiles">
        <div class="container">
            <div class="collection__list col__list">
                <div class="row row__custom">
                  <?php $k = 1; foreach ( $lp_tiles as $tile ): ?>
                    <?php $cat = (int) $tile['lp__tile-cat']; if ( ! $cat ) continue; ?>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * ( ( $k - 1 ) % 3 + 1 ); ?>s">
                          <a href="<?php the_permalink( $cat ); ?>" class="card__bl-2">
                              <div class="img__wrap">
                                  <div class="img__bl">
                                      <img src="<?php echo esc_url( get_the_post_thumbnail_url( $cat, 'pr-prev' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $cat ) ); ?>">
                                  </div>
                              </div>
                              <div class="card__header d-flex align-items-center">
                                  <div class="card__title"><?php echo esc_html( get_the_title( $cat ) ); ?></div>
                              </div>
                              <?php echo $lp_arrow; ?>
                          </a>
                          <?php // Below the card rather than card__descr, which the theme only reveals on hover. ?>
                          <?php if ( $tile['lp__tile-text'] ): ?>
                              <div class="lp__tile-text"><?php echo esc_html( $tile['lp__tile-text'] ); ?></div>
                          <?php endif; ?>
                          <?php if ( $tile['lp__tile-key'] ): ?>
                              <div class="lp__tile-key"><?php echo esc_html( $tile['lp__tile-key'] ); ?></div>
                          <?php endif; ?>
                      </div>
                  <?php $k++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ( $lp_use ): ?>
    <div class="post__box section sc__gray lp__use">
        <div class="container">
            <?php if ( get_field( 'lp__use-title' ) ): ?>
                <div class="sc__header">
                    <h2 class="sc__title wow fadeInUpDes" data-wow-delay="0.1s"><?php echo esc_html( get_field( 'lp__use-title' ) ); ?></h2>
                </div>
            <?php endif; ?>
            <div class="lp__table-wrap wow fadeInUpDes" data-wow-delay="0.2s">
                <table class="lp__table">
                    <?php if ( get_field( 'lp__use-col1' ) || get_field( 'lp__use-col2' ) ): ?>
                        <thead>
                            <tr>
                                <th><?php echo esc_html( get_field( 'lp__use-col1' ) ); ?></th>
                                <th><?php echo esc_html( get_field( 'lp__use-col2' ) ); ?></th>
                            </tr>
                        </thead>
                    <?php endif; ?>
                    <tbody>
                      <?php foreach ( $lp_use as $row ): ?>
                          <tr>
                              <td><?php echo esc_html( $row['lp__use-room'] ); ?></td>
                              <td><?php echo esc_html( $row['lp__use-solution'] ); ?></td>
                          </tr>
                      <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ( $lp_brands ): ?>
    <div class="post__box section lp__brands">
        <div class="container">
            <?php if ( get_field( 'lp__brands-title' ) ): ?>
                <div class="sc__header">
                    <h2 class="sc__title wow fadeInUpDes" data-wow-delay="0.1s"><?php echo esc_html( get_field( 'lp__brands-title' ) ); ?></h2>
                </div>
            <?php endif; ?>
            <div class="lp__brands-list d-flex flex-wrap">
              <?php $k = 1; foreach ( $lp_brands as $brand ): ?>
                  <div class="partners__item lp__brands-item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * (($k - 1) % 4 + 1); ?>s">
                      <a href="<?php the_permalink( $brand ); ?>" class="partners__item-bl d-flex align-items-center justify-content-center" title="<?php echo esc_attr( get_the_title( $brand ) ); ?>">
                          <div class="partners__item-img">
                              <img src="<?php echo esc_url( get_field( 'sp__logo-dark', $brand ) ); ?>" alt="<?php echo esc_attr( get_the_title( $brand ) ); ?>" width="100">
                          </div>
                          <div class="redirect__info d-flex align-items-center justify-content-center">
                            <?php _e( 'Детальніше', 'mdt' ); ?>
                          </div>
                      </a>
                  </div>
              <?php $k++; endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ( $lp_proj ): ?>
    <div class="post__box section sc__gray lp__proj">
        <div class="container">
            <?php if ( get_field( 'lp__proj-title' ) ): ?>
                <div class="sc__header">
                    <h2 class="sc__title wow fadeInUpDes" data-wow-delay="0.1s"><?php echo esc_html( get_field( 'lp__proj-title' ) ); ?></h2>
                </div>
            <?php endif; ?>
            <?php if ( get_field( 'lp__proj-text' ) ): ?>
                <div class="sc__descr lp__proj-text wow fadeInUpDes" data-wow-delay="0.15s"><?php echo esc_html( get_field( 'lp__proj-text' ) ); ?></div>
            <?php endif; ?>
            <div class="collection__list col__list">
                <div class="row row__custom">
                  <?php $k = 1; foreach ( $lp_proj as $row ): ?>
                    <?php
                    $proj = (int) $row['lp__proj-post'];
                    if ( ! $proj ) continue;
                    $prev  = get_field( 'proj__prev', $proj );
                    $lines = array_filter( array(
                        implode( ' · ', array_filter( array( $row['lp__proj-city'], $row['lp__proj-type'] ) ) ),
                        implode( ' · ', array_filter( array( $row['lp__proj-system'], $row['lp__proj-area'] ) ) ),
                    ) );
                    ?>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * ( ( $k - 1 ) % 3 + 1 ); ?>s">
                          <a href="<?php the_permalink( $proj ); ?>" class="card__bl-2">
                              <div class="img__wrap">
                                  <div class="img__bl">
                                      <img src="<?php echo esc_url( $prev ? $prev['sizes']['us-prev'] : get_the_post_thumbnail_url( $proj, 'pr-prev' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $proj ) ); ?>">
                                  </div>
                              </div>
                              <div class="card__header d-flex align-items-center">
                                  <div class="card__title"><?php echo esc_html( get_the_title( $proj ) ); ?></div>
                              </div>
                              <?php echo $lp_arrow; ?>
                          </a>
                          <?php foreach ( $lines as $line ): ?>
                              <div class="lp__tile-key"><?php echo esc_html( $line ); ?></div>
                          <?php endforeach; ?>
                      </div>
                  <?php $k++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
