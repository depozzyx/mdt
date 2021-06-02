<div class="wow fadeInUpDes" data-wow-delay="0.15s">
  <a href="<?php the_permalink(); ?>" class="card__bl-1">
    <div class="img__wrap">
      <div class="img__bl">
        <img src="<?php echo get_field('proj__prev')['sizes']['us-prev']; ?>">
      </div>
    </div>
    <div class="card__header d-flex align-items-center">
      <div class="card__title">
        <?php the_title(); ?>
      </div>
    </div>
  </a>
</div>