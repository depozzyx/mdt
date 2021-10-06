<?php /*Template Name: Single product template*/ ?>
<?php get_header(); ?>

<div class="section sc__inside sc__coll-main">
    <div class="container">
      <!--this_is_product-->
        <div class="breadcrumbs wow fadeInUpDes hidden-lg-up" data-wow-delay="0.1s">
            <ul>
                <li>
                    <a href="<?php echo get_permalink(lang_page_original_id(9)) ?>"><?php echo get_the_title(lang_page_original_id(9)) ?></a>  /
                </li>
                <li>
                  <?php _e('Колекція', 'mdt'); ?> <?php the_title(); ?>
                </li>
            </ul>
        </div>
        <div class="coll__main-header hidden-sm-up">
            <div class="coll__name seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                <h1>
                  <?php the_title(); ?>
                </h1>
            </div>
            <div class="coll__header-details">
              <?php if(get_field('p__price')): ?>
                  <div class="coll__price wow fadeInUpDes" data-wow-delay="0.2s">
                      <span class="price-before"><?php _e('від', 'mdt'); ?></span><?php the_field('p__price'); ?><span class="price-after">грн. / м²</span>
                  </div>
              <?php endif; ?>
              <?php if(get_field('p__time_d')): ?>
                  <div class="coll__delivery wow fadeInUpDes" data-wow-delay="0.3s">
                    <?php _e('Термін доставки', 'mdt'); ?>:<span><?php the_field('p__time_d'); ?></span>
                  </div>
              <?php endif; ?>
            </div>
        </div>
        <div class="coll__main-wrap d-flex">
            <div class="coll__main-left d-flex">
                <div class="coll__main-actions d-flex align-items-end wow fadeInUpDes" data-wow-delay="0.1s">
                    <div class="breadcrumbs wow fadeInUpDes hidden-lg-down" data-wow-delay="0.1s">
                        <ul>
                            <li>
                                <a href="<?php echo get_permalink(lang_page_original_id(9)) ?>"><?php echo get_the_title(lang_page_original_id(9)) ?></a>  /
                                <!--dg_crumb_url:<?php echo get_permalink(lang_page_original_id(9)) ?>;;dg_crumb_name:<?php echo get_the_title(lang_page_original_id(9)) ?>-->
                            </li>

                          <?  //dg_crumbs_start//
                              $parents = array_reverse(get_post_ancestors(get_field('p__cat')));
                              $id = icl_object_id(get_the_id(), 'page', true, 'uk');
                              $linkedCategory = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'bl__list_3_bl__item-cl-list' AND  meta_value LIKE '%".$id."%' LIMIT 1", ARRAY_A);
                              $parents = [];
                              if (isset($linkedCategory[0])){
                                $linkedCategoryId = $linkedCategory[0]['post_id'];
                                $parents[] = get_post($linkedCategoryId);
                                $iters = 0;
                                while (true){
                                  $parentId = wp_get_post_parent_id(get_post($linkedCategoryId));
                                  $linkedCategoryId = $parentId;
                                  $iters += 1;
                                  if ($iters >= 10 || $parentId == 0 || $parentId == $id){
                                    break;
                                  }
                                  if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false){
                                    $parentId = icl_object_id($parentId, 'page', true, 'ru');
                                  } else {
                                    $parentId = icl_object_id($parentId, 'page', true, 'uk');
                                  }
                                  $parents[] = get_post($parentId);
                                }
                              }
                              $parents = array_reverse($parents);
                              //dg_crumbs_start//
                            ?>

                            <?php /*$parents = array_reverse(get_post_ancestors(get_field('p__cat')));*/ ?>
                              <?php foreach ($parents as $parent): ?>
                                  <li>
                                      <a href="<?php echo get_permalink($parent); ?>"><?php echo get_the_title($parent); ?></a>  /
                                      <!--dg_crumb_url:<?php echo get_permalink($parent); ?>;;dg_crumb_name:<?php echo get_the_title($parent); ?>-->
                                  </li>
                              <?php endforeach; ?>
                            <li>
                                <?php _e('Колекція', 'mdt'); ?> <?php the_title(); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="coll__main-actions-bl">
                        <div class="share__box d-flex align-items-center wow fadeInUpDes" data-wow-delay="0.1s">
                            <div class="share__toggle d-flex align-items-center justify-content-center">
                                <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.4556 10.5866C11.592 10.5866 10.8205 10.991 10.3185 11.6187L6.27149 9.35809C6.36126 9.08698 6.41026 8.79625 6.41026 8.49689C6.41026 8.1944 6.36121 7.90618 6.26862 7.63244L10.3121 5.3752C10.8112 6.00605 11.5859 6.41337 12.4525 6.41337C13.9564 6.41337 15.1841 5.18878 15.1841 3.68169C15.1841 2.17463 13.9595 0.95 12.4525 0.95C10.9454 0.95 9.72076 2.17463 9.72076 3.68169C9.72076 3.98391 9.76973 4.27486 9.86229 4.54621L5.82214 6.80331C5.32288 6.16944 4.54818 5.7652 3.68169 5.7652C2.17778 5.7652 0.95 6.98979 0.95 8.49689C0.95 10.004 2.17782 11.2286 3.6848 11.2286C4.55152 11.2286 5.32616 10.8211 5.82825 10.1873L9.87175 12.4476C9.77895 12.7217 9.72699 13.0156 9.72699 13.3183C9.72699 14.8222 10.9516 16.05 12.4587 16.05C13.9657 16.05 15.1904 14.8254 15.1904 13.3183C15.1904 11.8112 13.9625 10.5866 12.4556 10.5866ZM12.4556 1.89406C13.4433 1.89406 14.2463 2.69705 14.2463 3.6848C14.2463 4.67255 13.4433 5.47554 12.4556 5.47554C11.4678 5.47554 10.6648 4.67255 10.6648 3.6848C10.6648 2.6971 11.4709 1.89406 12.4556 1.89406ZM3.6848 10.2876C2.69705 10.2876 1.89406 9.48464 1.89406 8.49689C1.89406 7.50913 2.69705 6.70615 3.6848 6.70615C4.67255 6.70615 5.47554 7.50913 5.47554 8.49689C5.47554 9.48458 4.66949 10.2876 3.6848 10.2876ZM12.4556 15.1059C11.4678 15.1059 10.6648 14.3029 10.6648 13.3152C10.6648 12.3274 11.4678 11.5245 12.4556 11.5245C13.4433 11.5245 14.2463 12.3274 14.2463 13.3152C14.2463 14.3029 13.4433 15.1059 12.4556 15.1059Z" fill="black" stroke="black" stroke-width="0.1"/>
                                </svg>

                                <div class="share__links d-flex align-items-center justify-content-center">
                                    <a href="viber://forward?text=<?php echo str_replace(' ', '%20', get_the_title()); ?>%20<?php echo get_permalink(); ?>">
                                        <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8468 9.48154C15.335 5.37221 14.6122 2.77788 13.3086 1.60447L13.3093 1.60378C11.2052 -0.400087 4.09924 -0.696679 1.58196 1.69378C0.451499 2.86311 0.053316 4.57925 0.00967953 6.7038C-0.033957 8.82904 -0.0857754 12.8102 3.62401 13.8902H3.62742L3.62401 15.5395C3.62401 15.5395 3.59878 16.2077 4.0256 16.342C4.03871 16.3463 4.05162 16.3502 4.06437 16.3537C4.10382 16.3744 4.14878 16.3835 4.19518 16.3771C4.56098 16.4005 4.87546 16.0243 6.00043 14.6784L6.00044 14.6784L6.20273 14.4365L6.26133 14.377C8.79642 14.5955 10.7425 14.0919 10.9638 14.0184C11.0096 14.003 11.0744 13.986 11.1549 13.9649L11.1549 13.9648C11.9754 13.7493 14.4196 13.1071 14.8468 9.48154ZM7.39396 2.93925C7.13146 2.93925 7.13146 3.34834 7.39396 3.35175C9.43056 3.36743 11.1078 4.78698 11.1262 7.39086C11.1262 7.66563 11.5285 7.66222 11.5251 7.38745H11.5244C11.5026 4.58175 9.67124 2.95493 7.39396 2.93925ZM10.0721 6.9591C10.066 7.23046 10.4676 7.24342 10.471 6.96864C10.5044 5.42159 9.55052 4.14727 7.75802 4.01295C7.49551 3.99386 7.46824 4.40636 7.73006 4.42545C9.28461 4.54341 10.1035 5.60432 10.0721 6.9591ZM8.81899 8.83678C8.96081 8.64519 9.30377 8.52383 9.64059 8.71883C10.0947 8.98405 10.6729 9.39656 11.056 9.77019C11.2722 9.95224 11.3213 10.2304 11.1733 10.5243V10.5291C10.8399 11.1366 10.2154 11.8075 9.57309 11.5934L9.56695 11.5838C8.91513 11.395 7.37967 10.5768 6.40944 9.77838C4.70288 8.38292 3.96439 6.61431 3.56051 5.64705C3.47578 5.44413 3.40578 5.27649 3.34466 5.15495C3.13807 4.49017 3.78375 3.84449 4.37352 3.49949H4.37966C4.6633 3.34608 4.93603 3.39722 5.11739 3.62426C5.15982 3.68363 5.21232 3.74742 5.27488 3.82343C5.46663 4.0564 5.75287 4.40418 6.1333 5.09154C6.32217 5.43995 6.20489 5.7945 6.01944 5.94177L5.64853 6.24859C5.46035 6.40473 5.48557 6.69586 5.48557 6.69586C5.48557 6.69586 6.03512 8.84633 8.09013 9.38974C8.09013 9.38974 8.37172 9.41565 8.5224 9.22065L8.81899 8.83678ZM8.03663 5.53345C8.69186 5.57163 9.00959 5.9139 9.04368 6.61686C9.05595 6.89164 9.45482 6.87254 9.44254 6.59777C9.39891 5.68004 8.92027 5.16867 8.05845 5.12095C7.79595 5.10527 7.7714 5.51777 8.03663 5.53345Z" fill="#343434"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="fb__share-js">
                                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.67617 2.49062H8.16724V0.105625C7.91 0.073125 7.02529 0 5.99494 0C3.84509 0 2.37239 1.24187 2.37239 3.52437V5.625H0V8.29125H2.37239V15H5.28104V8.29187H7.55747L7.91885 5.62563H5.28036V3.78875C5.28105 3.01812 5.50699 2.49062 6.67617 2.49062Z" fill="#343434"/>
                                        </svg>
                                    </a>
                                    <a target="_blank" href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()) ?>">
                                        <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.09047 8.69128L6.20483 13.9975L8.95745 11.2341L13.677 15L17 0L0 7.10885L4.09047 8.69128ZM12.1422 4.30365L6.9388 9.06836L6.2907 11.5206L5.09344 8.5151L12.1422 4.30365Z" fill="#343434"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="share__toggle-title">
                              <?php _e('Поділитися', 'mdt'); ?>
                            </div>
                        </div>
                        <div class="btn__icon-wrap d-flex">
                            <a href="#order-modal"
                               data-fancybox=""
                               data-touch="false"
                               data-modal-title="<?php the_field('modal__title-5', 'option'); ?>"
                               data-modal-src="<?php the_field('modal__src-5', 'option'); ?>" class="btn__icon d-flex align-items-center">
                                <div class="btn__icon-circle d-flex align-items-center justify-content-center">
                                    <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.3411 1H1.65887C1.29497 1 1 1.29497 1 1.65887V11.5417C1 11.9056 1.29497 12.2006 1.65887 12.2006H15.3411C15.7053 12.2006 16 11.9056 16 11.5417V1.65883C16 1.29493 15.705 1 15.3411 1ZM13.5437 2.3177L8.32538 6.73708L3.38394 2.3177H13.5437ZM14.6823 10.8828H2.3177V3.13206L7.87425 8.10161C8.11912 8.32078 8.48852 8.32561 8.73911 8.11327L14.6822 3.08028V10.8828H14.6823Z" fill="white" stroke="#343434" stroke-width="0.2"/>
                                    </svg>
                                </div>
                                <span><?php _e('Замовити каталог', 'mdt'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php $img_m = get_field('p__g'); ?>
                <div class="coll__media-box wow fadeInUpDes" data-count="<?php echo count($img_m); ?>" data-wow-delay="0.1s">
                    <div class="coll__media-box-list">
                        <?php if($img_m[0]): ?>
                            <div class="coll__media-img">
                              <img class="new_dg_img" src="<?php echo esc_url($img_m[0]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 1" title="<?php the_title(); ?> - Фото 1">
                              <!--dg_img_in_sitemap:<?php echo esc_url($img_m[0]['sizes']['pr-prev']); ?>-->
                                <div class="coll__media-info d-flex align-items-center justify-content-center">
                                  <?php echo $img_m[0]['title']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                      <?php if($img_m[1]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[1]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 2" title="<?php the_title(); ?> - Фото 2">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[1]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[1]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[2]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[2]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 3" title="<?php the_title(); ?> - Фото 3">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[2]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[2]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[3]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[3]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 4" title="<?php the_title(); ?> - Фото 4">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[3]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[3]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[4]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[4]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 5" title="<?php the_title(); ?> - Фото 5">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[4]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[4]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[5]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[5]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 6" title="<?php the_title(); ?> - Фото 6">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[5]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[5]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[6]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[6]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 7" title="<?php the_title(); ?> - Фото 7">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[6]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[6]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[7]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[7]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 8" title="<?php the_title(); ?> - Фото 8">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[7]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[7]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                      <?php if($img_m[8]): ?>
                          <div class="coll__media-img">
                            <img class="new_dg_img" src="<?php echo esc_url($img_m[8]['sizes']['pr-prev']); ?>" alt="<?php the_title(); ?> - Фото 9" title="<?php the_title(); ?> - Фото 9">
                            <!--dg_img_in_sitemap:<?php echo esc_url($img_m[8]['sizes']['pr-prev']); ?>-->
                              <div class="coll__media-info d-flex align-items-center justify-content-center">
                                <?php echo $img_m[8]['title']; ?>
                              </div>
                          </div>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="coll__main-right">
                <div class="coll__main-header hidden-sm-down">
                    <div class="coll__name seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        <h1>
                          <?php the_title(); ?>
                        </h1>
                    </div>
                    <?php if(get_field('p__price')): ?>
                        <div class="coll__price wow fadeInUpDes" data-wow-delay="0.2s">
                            <span class="price-before"><?php _e('від', 'mdt'); ?></span><?php the_field('p__price'); ?><span class="price-after">грн. / м²</span>
                        </div>
                    <?php endif; ?>
                    <?php if(get_field('p__time_d')): ?>
                        <div class="coll__delivery wow fadeInUpDes" data-wow-delay="0.3s">
                          <?php _e('Термін доставки', 'mdt'); ?>:<span><?php the_field('p__time_d'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if(get_field('p__descr')): ?>
                    <div class="coll__main-descr wow fadeInUpDes" data-wow-delay="0.4s">
                      <?php the_field('p__descr'); ?>
                    </div>
                <?php endif; ?>
                <div class="coll__main-bottom wow fadeInUpDes" data-wow-delay="0.5s">
                    <div class="coll__count-info">
                       <?php the_field('p__ex-info'); ?>
                    </div>
                    <div class="actions__wrap">
                        <a href="#order-modal"
                           data-fancybox=""
                           data-touch="false"
                           data-modal-title="<?php the_field('modal__title-2', 'option'); ?>"
                           data-modal-src="<?php the_field('modal__src-2', 'option'); ?>" class="btn__custom d-flex align-items-center justify-content-center">
                          <?php _e('Подивитися всю колекцію', 'mdt'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $img_g = get_field('p__garant'); ?>
<div class="section sc__coll-info sc__gray">
    <div class="container">
        <div class="tabs__wrap">
            <div class="tabs__list-wrap">
                <div class="tabs__list-scroll">
                    <div class="tabs__list wow fadeInUpDes" data-wow-delay="0.1s">
                        <ul>
                            <li class="active">
                                <a href="#teh"><?php _e('Характеристики', 'mdt'); ?></a>
                            </li>
                          <?php if(get_field('p__adv')): ?>
                              <li>
                                  <a href="#advants"><?php _e('Переваги', 'mdt'); ?></a>
                              </li>
                          <?php endif; ?>
                          <?php if(get_field('p__del') || get_field('p__pay')): ?>
                              <li>
                                  <a href="#delivery"><?php _e('Доставка і оплата', 'mdt'); ?></a>
                              </li>
                          <?php endif; ?>
                          <?php if($img_g): ?>
                              <li>
                                  <a href="#garant"><?php _e('Гарантія', 'mdt'); ?></a>
                              </li>
                          <?php endif; ?>
                          <?php if(get_field('p__video')): ?>
                              <li>
                                  <a href="#video"><?php _e('Відео', 'mdt'); ?></a>
                              </li>
                          <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab__content">
                <?php if(get_field('p__teh-list') && count(get_field('p__teh-list')) > 0): ?>
                    <div id="teh" class="tab__pane fadeIn animated active">
                        <div class="teh__box">
                            <div class="teh__box-title wow fadeInUpDes" data-wow-delay="0.1s">
                              <?php the_title(); ?>
                            </div>
                            <div class="teh__list">
                              <?php $i= 1; while ( have_rows('p__teh-list') ) : the_row();?>
                                  <div class="teh__item d-flex align-items-end justify-content-between wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                      <div class="teh__item-label">
                                        <?php the_sub_field('p__teh-info-label'); ?>
                                      </div>
                                      <div class="teh__item-value">
                                        <?php the_sub_field('p__teh-info-value'); ?>
                                      </div>
                                  </div>
                                <?php $i++; endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(get_field('p__adv')): ?>
                    <div id="advants" class="tab__pane fadeIn animated">
                        <div class="post__text"> <!-- post__text-cols -->
                          <?php the_field('p__adv'); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(get_field('p__del') || get_field('p__pay')): ?>
                    <div id="delivery" class="tab__pane fadeIn animated">
                        <div class="tab__content-cols">
                            <div class="row">
                              <?php if(get_field('p__del')): ?>
                                  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                      <div class="post__text">
                                        <?php the_field('p__del'); ?>
                                      </div>
                                  </div>
                              <?php endif; ?>
                              <?php if(get_field('p__pay')): ?>
                                  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                      <div class="post__text">
                                        <?php the_field('p__pay'); ?>
                                      </div>
                                  </div>
                              <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

                <div id="garant" class="tab__pane fadeIn animated">
                    <div class="sert__list">
                        <div class="row row__custom">
                            <?php if($img_g): ?>
                                <?php $j = 1; foreach( $img_g as $image ): ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 col__custom sert__item">
                                            <div class="sert__item-bl">
                                                <div class="sert__item-img">
                                                    <a href="<?php echo esc_url($image['sizes']['full']); ?>" data-fancybox="garant">
                                                        <img src="<?php echo esc_url($image['sizes']['g-prev']); ?>" alt="<?php echo esc_attr($image['title']); ?>">
                                                    </a>
                                                </div>
                                                <div class="sert__item-content">
                                                    <div class="sert__item-descr">
                                                      <?php echo esc_attr($image['title']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                  <?php $j++; endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div id="video" class="tab__pane fadeIn animated">
                    <div class="video__box">
                        <div class="video__box-title">
                            <?php _e('Відеоогляд', 'mdt'); ?> <?php the_title(); ?>
                        </div>
                        <div class="video__block">
                            <img src="<?php echo get_field('p__v-img')['sizes']['p-video']; ?>">
                            <div data-video="<?php the_field('p__video'); ?>" class="video__play">
                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 8L0.499999 15.7942L0.5 0.205771L14 8Z" fill="#343434"/>
                                </svg>
                            </div>
                            <div class="video__pl">
                                <div class="video__frame"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php if(get_field('faq__list') && count(get_field('faq__list')) > 0): ?>
    <div class="section sc__faq sc__common">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="sc__header">
                        <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                          <?php _e('Часто задавані питання', 'mdt'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="sc__content">
                        <div class="cat__toggle-box">
                            <div class="cat__toggle-list">
                              <?php $i= 1; while ( have_rows('faq__list') ) : the_row();?>
                                  <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $i; ?>s">
                                      <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                          <div class="cat__toggle-item-left d-flex align-items-center">
                                              <div class="cat__toggle-item-numb">
                                                <?php echo sprintf("%02d", $i); ?>.
                                              </div>
                                              <div class="cat__toggle-item-title">
                                                <?php the_sub_field('faq__item-q'); ?>
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
                                            <?php the_sub_field('faq__item-a'); ?>
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
<?php endif; ?>

<?php if(get_field('seo__title') && get_field('seo__text')): ?>
<!--     <div class="section sc__seo">
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
