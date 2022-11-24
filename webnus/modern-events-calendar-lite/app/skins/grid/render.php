<?php
/** no direct access **/
defined('MECEXEC') or die();

/** @var MEC_skin_grid $this */

$display_label = isset($this->skin_options['display_label']) ? $this->skin_options['display_label'] : false;
?>
<div class="swiper section-events-hero__slider js-events-hero-slider">
    <div class="swiper-wrapper">

        <?php
        foreach($this->events as $date):
            foreach($date as $event):

            $location_id = $this->main->get_master_location_id($event);
            $location = ($location_id ? $this->main->get_location_data($location_id) : array());

            $excerpt = get_the_excerpt($event->ID);

            if( empty($excerpt) ) {
                $event_end_date = !empty($event->date['end']['date']) ? strtotime($event->date['end']['date']) : '';
                $description = $event->data->content;
                $tags = array('</p>','<br />','<br>','<hr />','<hr>','</h1>','</h2>','</h3>','</h4>','</h5>','</h6>', '</li>');
                $description = str_replace($tags,"&#10;",$description);
                $description = strip_tags($description,'<br>, <br />');
            } else {
                $description = $excerpt;
            }


            $args = array(
                'post_type'      => 'mec-events',
                'posts_per_page' => 99,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'post_status'    => array( 'publish', 'private' )
                );
            $the_query = new WP_Query( $args );
            ?>
                <?php if($this->style == 'simple'): ?>
                    
                    <div class="swiper-slide">
                        <div class="events-card">
                            <div class="events-card__img">
                                <div class="background-img">
                                    <picture>
                                        <img src="/wp-content/themes/harbingermarketingtemplate/assets/theme/img/bg_intro.jpeg" alt="bg-image-card">
                                    </picture>
                                </div>
                            </div>

                            <div class="events-card__content">
                                <div class="events-card__title events-card__title--main section-title section-title--style5">
                                    <h3>
                                        <?= $event->data->title ?>
                                    </h3>
                                </div>

                                <?php if($description) : ?>
                                    <div class="events-card__description">
                                        <?= nl2br($description) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="events-card__footer">
                                <?php if(isset($event->data->locations) && $event->data->locations[$event->data->meta['mec_location_id']]): ?>
                                    <div class="events-card__footer-location">
                                        <span class="icon-wrap icon events-card__footer-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.7975 0.738281C7.48505 0.738281 3.97656 4.24677 3.97656 8.55917C3.97656 13.9794 11.8051 23.2383 11.8051 23.2383C11.8051 23.2383 19.6183 13.7129 19.6183 8.55917C19.6183 4.24677 16.11 0.738281 11.7975 0.738281ZM14.1572 10.8491C13.5065 11.4997 12.6521 11.825 11.7975 11.825C10.943 11.825 10.0883 11.4997 9.43787 10.8491C8.13667 9.54808 8.13667 7.43102 9.43787 6.12982C10.0679 5.49948 10.9061 5.15231 11.7975 5.15231C12.6889 5.15231 13.5268 5.49962 14.1572 6.12982C15.4584 7.43102 15.4584 9.54808 14.1572 10.8491Z" fill="#FF1E00"/>
                                            </svg>
                                        </span>

                                        <a class="events-card__footer-text">
                                            <?= $event->data->locations[$event->data->meta['mec_location_id']]['name'] ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if( isset($event_end_date) ): ?>
                                    <div class="events-card__footer-date">
                                        <span class="events-card__footer-text">
                                            <?= date('F jS, Y', $event_end_date) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="events-card__full">
                                <div class="events-card__full-body">
                                    <div class="events-card__full-content">
                                        <div class="events-card__full-title section-title section-title--style5">
                                            <h3>
                                                <?= $event->data->title ?>
                                            </h3>
                                        </div>

                                        <?php if($description) : ?>
                                            <div class="events-card__description">
                                                <?= nl2br($description) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>


                                    <div class="events-card__full-footer">
                                        <div class="event-info">
                                            <?php if(isset($event->data->locations) && $event->data->locations[$event->data->meta['mec_location_id']]): ?>
                                                <div class="event-info_location">
                                                        <span class="event-info_location-icon">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M11.7975 0.738281C7.48505 0.738281 3.97656 4.24677 3.97656 8.55917C3.97656 13.9794 11.8051 23.2383 11.8051 23.2383C11.8051 23.2383 19.6183 13.7129 19.6183 8.55917C19.6183 4.24677 16.11 0.738281 11.7975 0.738281ZM14.1572 10.8491C13.5065 11.4997 12.6521 11.825 11.7975 11.825C10.943 11.825 10.0883 11.4997 9.43787 10.8491C8.13667 9.54808 8.13667 7.43102 9.43787 6.12982C10.0679 5.49948 10.9061 5.15231 11.7975 5.15231C12.6889 5.15231 13.5268 5.49962 14.1572 6.12982C15.4584 7.43102 15.4584 9.54808 14.1572 10.8491Z" fill="white"/>
                                                            </svg>
                                                        </span>

                                                    <a href="https://www.google.com/maps/place/%D0%91%D0%B8%D1%80%D0%BC%D0%B8%D0%BD%D0%B3%D0%B5%D0%BC,+%D0%90%D0%BB%D0%B0%D0%B1%D0%B0%D0%BC%D0%B0,+%D0%A1%D0%A8%D0%90/@33.5311585,-86.9902221,11z/data=!3m1!4b1!4m5!3m4!1s0x888911df5885bfd3:0x25507409eaba54ce!8m2!3d33.5185892!4d-86.8103567" class="event-info_location-text" target="_blank">
                                                        <?= $event->data->locations[$event->data->meta['mec_location_id']]['name'] ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <div class="event-info_date">
                                                <span class="event-info_date-icon">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.8333 3.33325H4.16667C3.24619 3.33325 2.5 4.07944 2.5 4.99992V16.6666C2.5 17.5871 3.24619 18.3333 4.16667 18.3333H15.8333C16.7538 18.3333 17.5 17.5871 17.5 16.6666V4.99992C17.5 4.07944 16.7538 3.33325 15.8333 3.33325Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M13.332 1.66675V5.00008" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.66797 1.66675V5.00008" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M2.5 8.33325H17.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>

                                                <?php if( isset($event->date['start']['date']) ): ?>
                                                    <span class="event-info_date-text">
                                                        <?= date( 'F jS, Y', strtotime($event->date['start']['date']) ) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

<!--                                        --><?php //if(date('Y-m-d', $event_end_date) >= date('Y-m-d') && $event->data->meta['mec_read_more']): ?>
                                            <div class="events-card__button-wrapper">
                                                <a class="button button-bordered button-bordered-white events-card__button" data-toggle="modal" data-target="#modal-events-<?= $event->ID ?>">
                                                    <?php _e( 'View More', THEME_TEXTDOMAIN ) ?>
                                                </a>
                                            </div>
<!--                                        --><?php //endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <div class="container">
        <div class="swiper-controls swiper-controls--circle">
            <button class="swiper-button-prev">
                <?php hmt_svg_inline( get_stylesheet_directory() . '/assets/theme/img/icons/icon-arrow-left.svg' ); ?>
            </button>

            <button class="swiper-button-next">
                <?php hmt_svg_inline( get_stylesheet_directory() . '/assets/theme/img/icons/icon-arrow-left.svg' ); ?>
            </button>
        </div>
    </div>
</div>

<?php
foreach($this->events as $date):
    foreach($date as $event): ?>
        <?php get_template_part(
            'template-parts/blocks/section-events-hero/popups/section-events-hero',
            'popup',
            [
                'block_id' => $event->ID,
                'event' => $event,
            ]
        )
        ?>
    <?php endforeach; ?>
<?php endforeach; ?>
