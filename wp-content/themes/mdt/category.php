<?php /*Template Name: Single use template*/ ?>
<?php get_header(); ?>

<div class="section sc__inside sc__inside-top sc__post-header">
    <div class="breadcrumbs wow fadeInUpDes" data-wow-delay="0.1s">
        <ul>
            <li>
                <a href="/projects">Проекты</a>  /
            </li>
            <li>
              <?php the_title(); ?>
            </li>
        </ul>
    </div>
    <div class="container">
        <div class="post__header">
            <div class="content__wrap">
                <div class="row row__custom justify-content-end">
                    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 col__custom">
                        <div class="content__box">
                            <div class="post__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                                <h1>
                                  <?php the_title(); ?>
                                </h1>
                            </div>
                            <div class="post__img wow fadeInUpDes" data-wow-delay="0.3s">
                                <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'post-main'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="share__box d-flex align-items-center wow fadeInUpDes" data-wow-delay="0.1s">
                    <div class="share__toggle d-flex align-items-center justify-content-center">
                        <svg viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4556 10.5866C11.592 10.5866 10.8205 10.991 10.3185 11.6187L6.27149 9.35809C6.36126 9.08698 6.41026 8.79625 6.41026 8.49689C6.41026 8.1944 6.36121 7.90618 6.26862 7.63244L10.3121 5.3752C10.8112 6.00605 11.5859 6.41337 12.4525 6.41337C13.9564 6.41337 15.1841 5.18878 15.1841 3.68169C15.1841 2.17463 13.9595 0.95 12.4525 0.95C10.9454 0.95 9.72076 2.17463 9.72076 3.68169C9.72076 3.98391 9.76973 4.27486 9.86229 4.54621L5.82214 6.80331C5.32288 6.16944 4.54818 5.7652 3.68169 5.7652C2.17778 5.7652 0.95 6.98979 0.95 8.49689C0.95 10.004 2.17782 11.2286 3.6848 11.2286C4.55152 11.2286 5.32616 10.8211 5.82825 10.1873L9.87175 12.4476C9.77895 12.7217 9.72699 13.0156 9.72699 13.3183C9.72699 14.8222 10.9516 16.05 12.4587 16.05C13.9657 16.05 15.1904 14.8254 15.1904 13.3183C15.1904 11.8112 13.9625 10.5866 12.4556 10.5866ZM12.4556 1.89406C13.4433 1.89406 14.2463 2.69705 14.2463 3.6848C14.2463 4.67255 13.4433 5.47554 12.4556 5.47554C11.4678 5.47554 10.6648 4.67255 10.6648 3.6848C10.6648 2.6971 11.4709 1.89406 12.4556 1.89406ZM3.6848 10.2876C2.69705 10.2876 1.89406 9.48464 1.89406 8.49689C1.89406 7.50913 2.69705 6.70615 3.6848 6.70615C4.67255 6.70615 5.47554 7.50913 5.47554 8.49689C5.47554 9.48458 4.66949 10.2876 3.6848 10.2876ZM12.4556 15.1059C11.4678 15.1059 10.6648 14.3029 10.6648 13.3152C10.6648 12.3274 11.4678 11.5245 12.4556 11.5245C13.4433 11.5245 14.2463 12.3274 14.2463 13.3152C14.2463 14.3029 13.4433 15.1059 12.4556 15.1059Z" fill="black" stroke="black" stroke-width="0.1"/>
                        </svg>

                        <div class="share__links d-flex align-items-center justify-content-center">
                            <a href="#">
                                <svg width="15" height="17" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8468 9.48154C15.335 5.37221 14.6122 2.77788 13.3086 1.60447L13.3093 1.60378C11.2052 -0.400087 4.09924 -0.696679 1.58196 1.69378C0.451499 2.86311 0.053316 4.57925 0.00967953 6.7038C-0.033957 8.82904 -0.0857754 12.8102 3.62401 13.8902H3.62742L3.62401 15.5395C3.62401 15.5395 3.59878 16.2077 4.0256 16.342C4.03871 16.3463 4.05162 16.3502 4.06437 16.3537C4.10382 16.3744 4.14878 16.3835 4.19518 16.3771C4.56098 16.4005 4.87546 16.0243 6.00043 14.6784L6.00044 14.6784L6.20273 14.4365L6.26133 14.377C8.79642 14.5955 10.7425 14.0919 10.9638 14.0184C11.0096 14.003 11.0744 13.986 11.1549 13.9649L11.1549 13.9648C11.9754 13.7493 14.4196 13.1071 14.8468 9.48154ZM7.39396 2.93925C7.13146 2.93925 7.13146 3.34834 7.39396 3.35175C9.43056 3.36743 11.1078 4.78698 11.1262 7.39086C11.1262 7.66563 11.5285 7.66222 11.5251 7.38745H11.5244C11.5026 4.58175 9.67124 2.95493 7.39396 2.93925ZM10.0721 6.9591C10.066 7.23046 10.4676 7.24342 10.471 6.96864C10.5044 5.42159 9.55052 4.14727 7.75802 4.01295C7.49551 3.99386 7.46824 4.40636 7.73006 4.42545C9.28461 4.54341 10.1035 5.60432 10.0721 6.9591ZM8.81899 8.83678C8.96081 8.64519 9.30377 8.52383 9.64059 8.71883C10.0947 8.98405 10.6729 9.39656 11.056 9.77019C11.2722 9.95224 11.3213 10.2304 11.1733 10.5243V10.5291C10.8399 11.1366 10.2154 11.8075 9.57309 11.5934L9.56695 11.5838C8.91513 11.395 7.37967 10.5768 6.40944 9.77838C4.70288 8.38292 3.96439 6.61431 3.56051 5.64705C3.47578 5.44413 3.40578 5.27649 3.34466 5.15495C3.13807 4.49017 3.78375 3.84449 4.37352 3.49949H4.37966C4.6633 3.34608 4.93603 3.39722 5.11739 3.62426C5.15982 3.68363 5.21232 3.74742 5.27488 3.82343C5.46663 4.0564 5.75287 4.40418 6.1333 5.09154C6.32217 5.43995 6.20489 5.7945 6.01944 5.94177L5.64853 6.24859C5.46035 6.40473 5.48557 6.69586 5.48557 6.69586C5.48557 6.69586 6.03512 8.84633 8.09013 9.38974C8.09013 9.38974 8.37172 9.41565 8.5224 9.22065L8.81899 8.83678ZM8.03663 5.53345C8.69186 5.57163 9.00959 5.9139 9.04368 6.61686C9.05595 6.89164 9.45482 6.87254 9.44254 6.59777C9.39891 5.68004 8.92027 5.16867 8.05845 5.12095C7.79595 5.10527 7.7714 5.51777 8.03663 5.53345Z" fill="#343434"/>
                                </svg>
                            </a>
                            <a href="#">
                                <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.67617 2.49062H8.16724V0.105625C7.91 0.073125 7.02529 0 5.99494 0C3.84509 0 2.37239 1.24187 2.37239 3.52437V5.625H0V8.29125H2.37239V15H5.28104V8.29187H7.55747L7.91885 5.62563H5.28036V3.78875C5.28105 3.01812 5.50699 2.49062 6.67617 2.49062Z" fill="#343434"/>
                                </svg>
                            </a>
                            <a href="#">
                                <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.09047 8.69128L6.20483 13.9975L8.95745 11.2341L13.677 15L17 0L0 7.10885L4.09047 8.69128ZM12.1422 4.30365L6.9388 9.06836L6.2907 11.5206L5.09344 8.5151L12.1422 4.30365Z" fill="#343434"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="share__toggle-title">
                        Поделиться
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__post-content">
  <?php $i= 1; while ( have_rows('bl__list') ) : the_row();?>
    <?php if(get_sub_field('bl__item-type') === 'text'): ?>
          <div class="post__box">
              <div class="container">
                  <div class="content__wrap">
                      <div class="row row__custom justify-content-end">
                          <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 col__custom">
                              <div class="content__box">
                                  <div class="post__text wow fadeInUpDes" data-wow-delay="0.1s">
                                    <?php the_sub_field('bl__item-text'); ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    <?php endif; ?>

    <?php if(get_sub_field('bl__item-type') === 'slider'): ?>
          <div class="post__box">
              <div class="container">
                  <div class="media__slider-box">
                      <div class="media__slider">
                        <?php $images = get_sub_field('bl__item-sl'); ?>
                        <?php if($images): ?>
                          <?php $j = 1; foreach( $images as $image ): ?>
                                <div class="media__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $j; ?>s">
                                    <img src="<?php echo esc_url($image['sizes']['g-h']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                </div>
                            <?php $j++; endforeach; ?>
                        <?php endif; ?>
                      </div>
                      <div class="nav__box d-flex justify-content-center wow fadeInUpDes" data-wow-delay="0.2s">
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
              </div>
          </div>
    <?php endif; ?>
    <?php if(get_sub_field('bl__item-type') === 'g2'): ?>

          <div class="post__box">
              <div class="container">
                  <div class="media__list">
                      <div class="row row__custom">
                        <?php $images = get_sub_field('bl__item-g2'); ?>
                        <?php if($images): ?>
                          <?php $j = 1; foreach( $images as $image ): ?>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col__custom media__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $j; ?>s">
                                    <img src="<?php echo esc_url($image['sizes']['g-v']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                </div>
                            <?php $j++; endforeach; ?>
                        <?php endif; ?>
                      </div>
                  </div>
              </div>
          </div>
    <?php endif; ?>

    <?php if(get_sub_field('bl__item-type') === 'g1'): ?>

          <div class="post__box">
              <div class="container">
                  <div class="media__list">
                      <div class="row row__custom">
                        <?php $images = get_sub_field('bl__item-g1'); ?>
                        <?php if($images): ?>
                          <?php $j = 1; foreach( $images as $image ): ?>
                                <div class="col-12 col__custom media__item wow fadeInUpDes" data-wow-delay="<?php echo 0.1 * $j; ?>s">
                                    <img src="<?php echo esc_url($image['sizes']['g-h']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                </div>
                            <?php $j++; endforeach; ?>
                        <?php endif; ?>
                      </div>
                  </div>
              </div>
          </div>
    <?php endif; ?>

    <?php if(get_sub_field('bl__item-type') === 'cl'): ?>
          <div class="post__box section sc__gray">
              <div class="container">
                  <div class="sc__header">
                      <div class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                          Коллекции
                      </div>
                  </div>
                  <div class="collection__list col__list">
                      <div class="row row__custom align-items-center">
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.1s">
                              <a href="#" class="card__bl-3">
                                  <div class="img__wrap">
                                      <div class="img__bl">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/dist/img/col-img-1.jpg">
                                      </div>
                                  </div>
                                  <div class="card__header">
                                      <div class="card__title">
                                          Forbo Forte Graphic Reef
                                      </div>
                                      <div class="card__info">
                                          Цена от 784 <span>грн. / м²</span>
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
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.2s">
                              <a href="#" class="card__bl-3">
                                  <div class="img__wrap">
                                      <div class="img__bl">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/dist/img/col-img-2.jpg">
                                      </div>
                                  </div>
                                  <div class="card__header">
                                      <div class="card__title">
                                          Forbo Markant
                                      </div>
                                      <div class="card__info">
                                          Цена от 784 <span>грн. / м²</span>
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
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.3s">
                              <a href="#" class="card__bl-3">
                                  <div class="img__wrap">
                                      <div class="img__bl">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/dist/img/col-img-3.jpg">
                                      </div>
                                  </div>
                                  <div class="card__header">
                                      <div class="card__title">
                                          Forbo Forte Graphic Reef
                                      </div>
                                      <div class="card__info">
                                          Цена от 784 <span>грн. / м²</span>
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
                      </div>
                  </div>
              </div>
          </div>
    <?php endif; ?>

    <?php if(get_sub_field('bl__item-type') === 'ct'): ?>
          <div class="post__box section sc__gray">
              <div class="container">
                  <div class="sc__header">
                      <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                          <h2>
                              Категории товаров
                          </h2>
                      </div>
                  </div>
                  <div class="floor__list col__list">
                      <div class="row row__custom">
                          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.1s">
                              <a href="#" class="card__bl-2">
                                  <div class="img__wrap">
                                      <div class="img__bl">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/dist/img/c-2.jpg">
                                      </div>
                                  </div>
                                  <div class="card__header d-flex align-items-center">
                                      <div class="card__title">
                                          Ковролин
                                      </div>
                                  </div>
                                  <div class="card__descr">
                                      С другой стороны укрепление и развитие структуры обеспечивает широкому кругу.
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
                          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom col__item wow fadeInUpDes" data-wow-delay="0.2s">
                              <a href="#" class="card__bl-2">
                                  <div class="img__wrap">
                                      <div class="img__bl">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/dist/img/c-2.jpg">
                                      </div>
                                  </div>
                                  <div class="card__header d-flex align-items-center">
                                      <div class="card__title">
                                          Линолеум
                                      </div>
                                  </div>
                                  <div class="card__descr">
                                      С другой стороны укрепление и развитие структуры обеспечивает широкому кругу.
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
                      </div>
                  </div>
              </div>
          </div>
    <?php endif; ?>

    <?php if(get_sub_field('bl__item-type') === 'pr'): ?>
          <div class="post__box section sc__box-partners">
              <div class="container">
                  <div class="sc__header d-flex align-items-center justify-content-between">
                      <div class="sc__title wow fadeInUpDes" data-wow-delay="0.1s">
                          Поставщики
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
              </div>
          </div>
    <?php endif; ?>

    <?php $i++; endwhile; ?>

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
                        <a href="#" class="link__custom link__custom-dark">
                          <?php the_field('op__f-link', 'option') ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="static__img wow fadeInUpDes" data-wow-delay="0.3s">
                    <div class="img__wrap">
                        <div class="img__bl">
                            <img src="<?php echo get_field('op__f-img', 'option')['sizes']['f-prev'] ?>">
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
                Последние новости
            </div>
        </div>
        <div class="news__list">
            <div class="row row__custom">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="0.1s">
                    <a href="#" class="news__item-block">
                        <div class="news__item-img">
                            <div class="img__wrap">
                                <div class="img__bl">
                                    <img src="/img/n-1.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="news__item-content">
                            <div class="news__item-title">
                                <span>Идейные соображения высшего порядка, а также постоянное ...</span>
                            </div>
                            <div class="news__item-date">
                                01.09.2020
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="0.2s">
                    <a href="#" class="news__item-block">
                        <div class="news__item-img">
                            <div class="img__wrap">
                                <div class="img__bl">
                                    <img src="/img/n-1.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="news__item-content">
                            <div class="news__item-title">
                                <span>Равным образом постоянный количественный рост</span>
                            </div>
                            <div class="news__item-date">
                                01.09.2020
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="0.3s">
                    <a href="#" class="news__item-block">
                        <div class="news__item-img">
                            <div class="img__wrap">
                                <div class="img__bl">
                                    <img src="/img/n-1.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="news__item-content">
                            <div class="news__item-title">
                                <span>Повседневная практика показывает, что дальнейшее развитие</span>
                            </div>
                            <div class="news__item-date">
                                01.09.2020
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 col__custom new__item wow fadeInUpDes" data-wow-delay="0.4s">
                    <a href="#" class="news__item-block">
                        <div class="news__item-img">
                            <div class="img__wrap">
                                <div class="img__bl">
                                    <img src="/img/n-1.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="news__item-content">
                            <div class="news__item-title">
                                <span>Идейные соображения высшего</span>
                            </div>
                            <div class="news__item-date">
                                01.09.2020
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section sc__faq sc__common sc__gray">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="sc__header">
                    <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                        Чacтo зaдaвaemыe вoпpocы
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="sc__content">
                    <div class="cat__toggle-box">
                        <div class="cat__toggle-list">
                            <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="0.1s">
                                <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                    <div class="cat__toggle-item-left d-flex align-items-center">
                                        <div class="cat__toggle-item-numb">
                                            01.
                                        </div>
                                        <div class="cat__toggle-item-title">
                                            Какие бренды покупают в группе Ковролин?
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
                                        <p>
                                            Популярные бренды:
                                        </p>
                                        <ul>
                                            <li>
                                                Betap
                                            </li>
                                            <li>
                                                Carpet
                                            </li>
                                            <li>
                                                Confetti
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="0.2s">
                                <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                    <div class="cat__toggle-item-left d-flex align-items-center">
                                        <div class="cat__toggle-item-numb">
                                            02.
                                        </div>
                                        <div class="cat__toggle-item-title">
                                            Какие товары купить в 2020 году в группе Ковролин?
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
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                        </p>
                                        <p>
                                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="0.3s">
                                <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                    <div class="cat__toggle-item-left d-flex align-items-center">
                                        <div class="cat__toggle-item-numb">
                                            03.
                                        </div>
                                        <div class="cat__toggle-item-title">
                                            Товары с бесплатным самовывозом в группе Ковролин?
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
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                        </p>
                                        <p>
                                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="cat__toggle-item wow fadeInUpDes" data-wow-delay="0.4s">
                                <div class="cat__toggle-item-header d-flex align-items-center justify-content-between toggle__content-js">
                                    <div class="cat__toggle-item-left d-flex align-items-center">
                                        <div class="cat__toggle-item-numb">
                                            04.
                                        </div>
                                        <div class="cat__toggle-item-title">
                                            Какие бренды покупают в группе Ковролин?
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
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                        </p>
                                        <p>
                                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
