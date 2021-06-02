<?php /*Template Name: Contacts page*/ ?>
<?php get_header(); ?>

<div class="section sc__contacts">
    <div class="container">
        <div class="contacts__box">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="sc__header">
                        <div class="sc__title seo__item wow fadeInUpDes" data-wow-delay="0.1s">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="contacts__list">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-7 col-sm-12 col-12 contacts__item wow fadeInUpDes" data-wow-delay="0.1s">
                                <div class="contacts__item-label">
                                    <?php _e('Телефони', 'mdt'); ?>
                                </div>
                                <div class="contacts__item-value">
                                    <a href="tel:<?php echo formatPhone(get_field('ct__ph-1')) ?>"><?php the_field('ct__ph-1') ?></a>, <a href="tel:<?php echo formatPhone(get_field('ct__ph-2')) ?>"><?php the_field('ct__ph-2') ?></a>
                                </div>
                                <div class="actions__list d-flex">
                                    <?php if(get_field('ct__telegram')): ?>
                                        <div class="actions__link-item">
                                            <a href="<?php the_field('ct__telegram'); ?>" class="link__custom link__custom-small link__custom-dark">
                                                Telegram
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                  <?php if(get_field('ct__viber')): ?>
                                      <div class="actions__link-item">
                                          <a href="viber://chat/?number=<?php the_field('ct__viber'); ?>" class="link__custom link__custom-small link__custom-dark">
                                              Viber
                                          </a>
                                      </div>
                                  <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-5 col-sm-12 col-12 contacts__item wow fadeInUpDes" data-wow-delay="0.2s">
                                <div class="contacts__item-label">
                                    E-mail
                                </div>
                                <div class="contacts__item-value">
                                    <a href="mailto:<?php the_field('ct__email') ?>"><?php the_field('ct__email') ?></a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-7 col-sm-12 col-12 contacts__item wow fadeInUpDes" data-wow-delay="0.3s">
                                <div class="contacts__item-label">
                                    <?php _e('Адреса', 'mdt'); ?>
                                </div>
                                <div class="contacts__item-value">
                                  <?php the_field('ct__address') ?>
                                </div>
                                <div class="actions__list d-flex">
                                    <div class="actions__link-item">
                                        <a href="<?php the_field('ct__gmaps') ?>" class="link__custom link__custom-dark">
                                          <?php _e('Відкрити на карті', 'mdt'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-5 col-sm-12 col-12 contacts__item wow fadeInUpDes" data-wow-delay="0.4s">
                                <div class="contacts__item-label">
                                    <?php _e('Час роботи', 'mdt'); ?>
                                </div>
                                <div class="contacts__item-value">
                                  <?php the_field('ct__worktime') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="social d-flex">
                <?php if(get_field('ct__facebook')): ?>
                    <a target="_blank" href="<?php the_field('ct__facebook'); ?>" class="d-flex align-items-center justify-content-center wow fadeInUpDes" data-wow-delay="0.1s">
                        <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.12124 2.65667H8.71173V0.112667C8.43733 0.078 7.49364 0 6.3946 0C4.10143 0 2.53055 1.32467 2.53055 3.75933V6H0V8.844H2.53055V16H5.63311V8.84467H8.06131L8.44677 6.00067H5.63239V4.04133C5.63312 3.21933 5.87412 2.65667 7.12124 2.65667Z" fill="#343434"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if(get_field('ct__instagram')): ?>
                    <a target="_blank" href="<?php the_field('ct__instagram'); ?>" class="d-flex align-items-center justify-content-center wow fadeInUpDes" data-wow-delay="0.2s">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.3697 14.6459C3.62602 14.9117 3.94182 15.1149 4.28876 15.2431C4.57011 15.3525 4.99215 15.4838 5.77371 15.5181L3.3697 14.6459ZM3.3697 14.6459C3.10397 14.3864 2.90076 14.0738 2.77254 13.7269L3.3697 14.6459ZM17.0093 5.70276L16.9593 5.70496L17.0093 5.70275C16.9716 4.8477 16.8332 4.25773 16.6341 3.74563C16.4284 3.20148 16.1119 2.71409 15.6972 2.30885C15.2921 1.89753 14.8016 1.57787 14.2636 1.37532C13.7487 1.17627 13.1618 1.03783 12.3067 1.00012C11.449 0.959402 11.176 0.95 9.00311 0.95C6.83013 0.95 6.55733 0.959404 5.70276 0.996941L5.70275 0.996942C4.84769 1.03464 4.25772 1.17319 3.74575 1.37218C3.20148 1.57786 2.71409 1.89432 2.30885 2.30899C1.89753 2.7141 1.57799 3.2046 1.37532 3.74265C1.17627 4.25768 1.03783 4.84442 1.00012 5.6995C0.959402 6.55728 0.95 6.83022 0.95 9.00311C0.95 11.1761 0.959404 11.4489 0.996941 12.3035L0.996942 12.3035C1.03464 13.1585 1.17319 13.7485 1.3723 14.2606C1.57805 14.8049 1.89762 15.2922 2.30893 15.6973C2.71405 16.1087 3.20457 16.4284 3.74265 16.6309C4.2577 16.83 4.84446 16.9684 5.6997 17.0061C6.55415 17.0438 6.82709 17.0531 9.00006 17.0531C11.173 17.0531 11.4458 17.0438 12.3004 17.0061C13.1556 16.9684 13.7456 16.8299 14.2577 16.6308C15.3462 16.2099 16.2069 15.3492 16.6278 14.2607L16.6278 14.2607C16.8268 13.7456 16.9654 13.1587 17.0031 12.3035C17.0406 11.4489 17.05 11.1761 17.05 9.00311C17.05 6.83045 17.0468 6.55748 17.0093 5.70276ZM15.4683 12.2365L15.4683 12.2365C15.4341 13.0125 15.304 13.4291 15.1965 13.7056L15.1964 13.7057C14.9326 14.3897 14.3897 14.9326 13.7057 15.1964L13.7056 15.1965C13.4292 15.304 13.0095 15.4341 12.2365 15.4682L12.2365 15.4682C11.3935 15.5057 11.1412 15.515 9.00629 15.515C6.87133 15.515 6.61586 15.5057 5.77594 15.4682L5.7759 15.4682C4.99997 15.4341 4.58331 15.304 4.30688 15.1965L4.30609 15.1962C3.96597 15.0705 3.65661 14.8714 3.40568 14.6112L3.4057 14.6112L3.40463 14.6102C3.14418 14.3558 2.94508 14.0495 2.81944 13.7095L2.81945 13.7095L2.81914 13.7088C2.71165 13.4324 2.58155 13.0125 2.54748 12.2397L2.54748 12.2397C2.50992 11.3967 2.50064 11.1443 2.50064 9.00934C2.50064 6.87438 2.50992 6.61892 2.54748 5.77912L2.54748 5.77908C2.58155 5.00315 2.71164 4.58648 2.81914 4.31005L2.81915 4.31006L2.81944 4.30926C2.94514 3.96905 3.14422 3.65991 3.40738 3.40893L3.4074 3.40896L3.40865 3.40767C3.66287 3.14723 3.96919 2.94814 4.30924 2.82262L4.30925 2.82263L4.31005 2.82232C4.58646 2.71482 5.0063 2.58472 5.77909 2.55054L5.7791 2.55053C6.62209 2.51309 6.87452 2.50369 9.00934 2.50369C11.1475 2.50369 11.3998 2.51309 12.2397 2.55053L12.2397 2.55054C13.0157 2.58473 13.4323 2.71482 13.7088 2.82232L13.7088 2.82232L13.7096 2.82262C14.0497 2.9482 14.359 3.14733 14.6099 3.40746L14.6099 3.40748L14.611 3.40852C14.8715 3.66287 15.0706 3.9692 15.1962 4.30926L15.1965 4.31005C15.304 4.58646 15.4341 5.00618 15.4683 5.77909L15.4683 5.7791C15.5057 6.62209 15.5151 6.87439 15.5151 9.00934C15.5151 11.1443 15.5057 11.3935 15.4683 12.2365Z" fill="black" stroke="black" stroke-width="0.1"/>
                            <path d="M9.0016 4.84209C6.70443 4.84209 4.84062 6.70577 4.84062 9.00306C4.84062 11.3004 6.70443 13.164 9.0016 13.164C11.2989 13.164 13.1626 11.3004 13.1626 9.00306C13.1626 6.70577 11.2989 4.84209 9.0016 4.84209ZM9.0016 11.6198C7.55684 11.6198 6.38491 10.4479 6.38491 9.00306C6.38491 7.55818 7.55684 6.38638 9.0016 6.38638C10.4465 6.38638 11.6183 7.55818 11.6183 9.00306C11.6183 10.4479 10.4465 11.6198 9.0016 11.6198Z" fill="black" stroke="black" stroke-width="0.1"/>
                            <path d="M13.28 5.73996C13.8378 5.73996 14.2899 5.28784 14.2899 4.73023C14.2899 4.1725 13.8378 3.72051 13.28 3.72051C12.7224 3.72051 12.2703 4.1725 12.2703 4.73023C12.2703 5.28784 12.7224 5.73996 13.28 5.73996Z" fill="black" stroke="black" stroke-width="0.1"/>
                        </svg>
                    </a>
                <?php endif; ?>


            </div>
        </div>

    </div>
</div>
<div class="section sc__form">
    <div class="sc__bg wow fadeInUpDes" data-wow-delay="0.1s" style="background-image: url(<?php echo get_template_directory_uri();?>/assets/dist//img/ct-bg.jpg)"></div>
    <div class="container">
        <div class="form__box">
            <div class="form__tabs wow fadeInUpDes" data-wow-delay="0.1s">
                <ul>
                    <li class="active">
                        <a href="#support"><?php the_field('ct__form-1'); ?></a>
                    </li>
                    <li>
                        <a href="#team"><?php the_field('ct__form-2'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="form__tab-content">
                <div id="support" class="form__tab-pane fadeIn animated active">
                  <?php
                      switch(ICL_LANGUAGE_CODE){
                        case 'uk':
                          echo do_shortcode('[contact-form-7 id="133" title="Задати питання UA"]');
                          break;
                        case 'ru':
                          echo do_shortcode('[contact-form-7 id="6510" title="Задати питання RU"]');
                          break;
                      }
                  ?>
                </div>
                <div id="team" class="form__tab-pane fadeIn animated">
                  <?php
                  switch(ICL_LANGUAGE_CODE){
                    case 'uk':
                      echo do_shortcode('[contact-form-7 id="6511" title="Стати членом команди"]');
                      break;
                    case 'ru':
                      echo do_shortcode('[contact-form-7 id="8248" title="Стати членом команди"]');
                      break;
                  }
                  ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
