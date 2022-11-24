<?php
/** no direct access **/
defined('MECEXEC') or die();

/** @var MEC_skin_monthly_view $this */

// Custom Modals
global $customModals;

$customModals = '';
// table headings
$headings = $this->main->get_weekday_labels();
echo '<dl class="mec-calendar-table-head"><dt class="mec-calendar-day-head">'.implode('</dt><dt class="mec-calendar-day-head">', $headings).'</dt></dl>';

// Start day of week
$week_start = $this->main->get_first_day_of_week();

// Single Event Display Method
$target_set = isset($this->skin_options['sed_method']) ? $this->skin_options['sed_method'] : false;
$target_url = ($target_set == 'new') ? 'target="_blank"' : '';

$this->localtime = isset($this->skin_options['include_local_time']) ? $this->skin_options['include_local_time'] : false;
$display_label = isset($this->skin_options['display_label']) ? $this->skin_options['display_label'] : false;
$reason_for_cancellation = isset($this->skin_options['reason_for_cancellation']) ? $this->skin_options['reason_for_cancellation'] : false;

// days and weeks vars
$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
$days_in_previous_month = date('t', strtotime('-1 month', strtotime($this->active_day)));

$days_in_this_week = 1;
$day_counter = 0;

if($week_start == 0) $running_day = $running_day; // Sunday
elseif($week_start == 1) // Monday
{
    if($running_day != 0) $running_day = $running_day - 1;
    else $running_day = 6;
}
elseif($week_start == 6) // Saturday
{
    if($running_day != 6) $running_day = $running_day + 1;
    else $running_day = 0;
}
elseif($week_start == 5) // Friday
{
    if($running_day < 4) $running_day = $running_day + 2;
    elseif($running_day == 5) $running_day = 0;
    elseif($running_day == 6) $running_day = 1;
}

?>
<div class="mec-calendar-row__wrapper">
    <dl class="mec-calendar-row">
        <?php
            // print "blank" days until the first of the current week
            for($x = 0; $x < $running_day; $x++)
            {
                $cur_day = $days_in_previous_month - ($running_day-1-$x);
                $time = strtotime((($month == '01') ? $year-1 : $year).'-'.(($month == '01') ? 12 : $month-1).'-'.$cur_day);
                $today_day = date('D', $time);
                echo '<dt class="mec-table-nullday mec-table-nullday--not-cur-month">'.$cur_day.'<span class="current-day">'. $today_day .'</span></dt>';
                $days_in_this_week++;
            }

            // keep going with days ....
            for($list_day = 1; $list_day <= $days_in_month; $list_day++)
            {
                $time = strtotime($year.'-'.$month.'-'.$list_day);

                $current_date = date('Y-m-d', time());
                $today = date('Y-m-d', $time);
                $today_day = date('D', $time);
                $day_id = date('Ymd', $time);
                $selected_day = (str_replace('-', '', $this->active_day) == $day_id && $month == date('m') ) ? ' mec-selected-day' : '';
                $selected_day_date = (str_replace('-', '', $this->active_day) == $day_id) ? '' : '';

                // Print events
                if(isset($events[$today]) and count($events[$today]))
                {
                    if ($current_date == $today || strtotime($current_date) <= strtotime($today)) {
                        echo '<dt class="mec-calendar-day'.$selected_day.' mec-calendar-day--with-events" data-mec-cell="'.$day_id.'" data-day="'.$list_day.'" data-month="'.date('Ym', $time).'"><div class="'.$selected_day_date.' date-number date-number--current">'.$list_day.'</div>';
                    } else { //$current_date != $today && strtotime($current_date) >= strtotime($today) // prev date
                        echo '<dt class="mec-table-nullday"><div class="date-number">'.$list_day.'</div><span class="current-day">'. $today_day .'</span>';
                    }
                    echo '<span class="current-day">'. $today_day .'</span>';
                    echo '<div class="events-list-for-day">';
                    foreach($events[$today] as $event)
                    {
                        $all_day_event = $event->data->meta['mec_date']['allday'];
                        $start_time = "{$event->data->meta['mec_date']['start']['hour']}:{$event->data->meta['mec_date']['start']['minutes']} {$event->data->meta['mec_date']['start']['ampm']}";
                        $end_time = "{$event->data->meta['mec_date']['end']['hour']}:{$event->data->meta['mec_date']['end']['minutes']} {$event->data->meta['mec_date']['end']['ampm']}";
                        $hide_time = $event->data->meta['mec_hide_time'];
                        $hide_end_time = $event->data->meta['mec_hide_end_time'];
                        
                        // Event Dom Element Id For Popups
                        $event_modal_id = $day_id . '-' . $event->data->ID;

                        $event_color = isset($event->data->meta['mec_color']) ? '#'.$event->data->meta['mec_color'] : '';

                        // Event Content
                        if(!$this->cache->has($event->data->ID.'_content'))
                        {
                            $event_content = ((isset($event->data->content) and trim($event->data->content) != '') ? $this->main->mec_content_html($event->data->content, 320) : '');
                            $this->cache->set($event->data->ID.'_content', $event_content);
                        }
                        else $event_content = $this->cache->get($event->data->ID.'_content');
                        $mec_date = $event->data->meta['mec_date'];
                        
                        echo '<div class="event-content-wrapper '.((isset($event->data->meta['event_past']) and trim($event->data->meta['event_past'])) ? 'mec-past-event ' : '').'ended-relative simple-skin-ended">';

                        echo '<h4 class="mec-event-title" data-toggle="modal" data-target="#event-' . $event_modal_id . '" data-event-id="' . $event->data->ID . '">'.$event->data->title.'</h4>'.$this->main->get_normal_labels($event, $display_label).$this->main->display_cancellation_reason($event, $reason_for_cancellation);

                        do_action('mec_shortcode_virtual_badge', $event->data->ID);
                        if( !$all_day_event && !$hide_time ){
                            if(!$hide_end_time){
                                echo '<div class="time">'. hmt_render_event_time($mec_date) .'</div>';
                            }else{
                                echo '<div class="time">'. $start_time .'</div>';
                            }
                        }
                        echo '</div>';

                        $tooltip_content = '';
                        $tooltip_content .= !empty( $event->data->title) ? '<div class="mec-tooltip-event-title">'.$event->data->title.'</div>' : '';
                        $tooltip_content .= trim($start_time) ? '<div class="mec-tooltip-event-time"><i class="mec-sl-clock-o"></i> '.$start_time.(trim($end_time) ? ' - '.$end_time : '').'</div>' : '';

                        if($this->display_price and isset($event->data->meta['mec_cost']) and $event->data->meta['mec_cost'] != '')
                        {
                            // Event Content
                            if(!$this->cache->has($event->data->ID.'_cost'))
                            {
                                $event_cost = (is_numeric($event->data->meta['mec_cost']) ? $this->main->render_price($event->data->meta['mec_cost']) : $event->data->meta['mec_cost']);
                                $this->cache->set($event->data->ID.'_cost', $event_cost);
                            }
                            else $event_cost = $this->cache->get($event->data->ID.'_cost');

                            $tooltip_content .= '<div class="mec-price-details">
                                <i class="mec-sl-wallet"></i>
                                <span>'.$event_cost.'</span>
                            </div>';
                        }

                        $tooltip_content .= (!empty($event->data->thumbnails['thumbnail']) || !empty($event->data->content)) ? '<div class="mec-tooltip-event-content">' : '';
                        $tooltip_content .= !empty($event->data->thumbnails['thumbnail']) ? '<div class="mec-tooltip-event-featured">'.$event->data->thumbnails['thumbnail'].'</div>' : '';
                        $tooltip_content .= !empty($event->data->content) ? '<div class="mec-tooltip-event-desc">'.$event_content.' , ...</div>' : '';
                        if($this->localtime) $tooltip_content .= $this->main->module('local-time.type2', array('event'=>$event));
                        $tooltip_content .= (!empty($event->data->thumbnails['thumbnail']) || !empty($event->data->content)) ? '</div>' : '';
                        $tooltip_content .= $this->booking_button($event);

                        // MEC Schema
                        do_action('mec_schema', $event);

                        // Calendar Event Popup
                        // EDIT CODE HERE
                        ob_start();
                        ?>
                        <div class="modal fade event-modal" id="event-<?= $event_modal_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn modal-close svg-icon" data-dismiss="modal" aria-label="<?= __('Close popup', THEME_TEXTDOMAIN) ?>">
                                            <?= hmt_get_svg_inline(get_template_directory_uri() . "/assets/theme/img/icons/icon-close.svg"); ?>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="team-member-card">
                                            <?php if (has_post_thumbnail( $event->ID ) ): ?>
                                                <div class="team-member-card__img">
                                                    <div class="background-img">
                                                        <picture>
                                                            <img src="<?= wp_get_attachment_image_src( get_post_thumbnail_id( $event->ID ), 'large' )[0] ?>" alt="">
                                                        </picture>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="team-member-card__content <?= has_post_thumbnail( $event->ID ) ? '' : 'full-width' ?>">
                                                <div class="team-member-card__date-and-time">
                                                    <?php if(date('Y-m-d', $time) > date('Y-m-d') && $event->data->meta['mec_read_more']): ?>
                                                        <a class="button button-bordered button-bordered-white-dark" href="<?= $event->data->meta['mec_read_more'] ?>" target="_blank">
                                                            <?= __('Register', THEME_TEXTDOMAIN) ?>
                                                        </a>
                                                    <?php endif; ?>

                                                    <div class="block-content">
                                                        <?php if($event->data->locations[$event->data->meta['mec_location_id']]): ?>
                                                            <p class="team-member-card__location">
                                                                <span class="icon-wrap">
                                                                    <?= hmt_get_svg_inline(get_template_directory_uri() . "/assets/theme/img/icons/icon-position-marker-white.svg"); ?>
                                                                </span>

                                                                <?= $event->data->locations[$event->data->meta['mec_location_id']]['name'] ?>
                                                            </p>
                                                        <?php endif; ?>

                                                        <p class="team-member-card__date">
                                                            <span class="icon-wrap">
                                                                <?= hmt_get_svg_inline(get_template_directory_uri() . "/assets/theme/img/icons/icon-calendar.svg"); ?>
                                                            </span>

                                                            <?= date('F jS, Y', $time) ?>
                                                        </p>

                                                        <?php if( !$all_day_event && !$hide_time): ?>
                                                            <p class="team-member-card__time">
                                                                <span class="icon-wrap">
                                                                    <?= hmt_get_svg_inline(get_template_directory_uri() . "/assets/theme/img/icons/icon-clock.svg"); ?>
                                                                </span>

                                                                <?php if( $hide_end_time ): ?>
                                                                    <?= $start_time ?>
                                                                <?php else: ?>
                                                                    <?= hmt_render_event_time($mec_date) ?>
                                                                <?php endif; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <h3 class="team-member-card__title">
                                                    <?= $event->data->title ?>
                                                </h3>

                                                <div class="team-member-card__body">
                                                    <div class="scrollbar-outer">
                                                        <div class="team-member-card__description text-content">
                                                            <?= wpautop($event->data->content) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

	                    $customModals .= ob_get_clean();
                    }

                    echo '</div>';
                    echo '</dt>';
                }
                else if($current_date != $today && strtotime($current_date) >= strtotime($today))
                {
                    echo '<dt class="mec-table-nullday"><div class="date-number">'.$list_day.'</div><span class="current-day">'. $today_day .'</span></dt>';
                    echo '</dt>';
                }
                else
                {
                    echo '<dt class="mec-calendar-day'.$selected_day.'" data-mec-cell="'.$day_id.'" data-day="'.$list_day.'" data-month="'.date('Ym', $time).'"><div class="date-number">'.$list_day. '</div><span class="current-day">'. $today_day .'</span></dt>';
                    echo '</dt>';
                }

                if($running_day == 6)
                {
                    echo '</dl>';
                    
                    if((($day_counter+1) != $days_in_month) or (($day_counter+1) == $days_in_month and $days_in_this_week == 7))
                    {
                        echo '<dl class="mec-calendar-row">';
                    }

                    $running_day = -1;
                    $days_in_this_week = 0;
                }

                $days_in_this_week++; $running_day++; $day_counter++;
            }

            // finish the rest of the days in the week
            if($days_in_this_week < 8)
            {
                for($x = 1; $x <= (8 - $days_in_this_week); $x++)
                {
                    $cur_day = $days_in_previous_month - ($running_day-1-$x);
                    $time = strtotime((($month == '12') ? $year+1 : $year).'-'.(($month == '12') ? '01' : $month+1).'-'.$x);
                    $today_day = date('D', $time);
                    echo '<dt class="mec-table-nullday mec-table-nullday--not-cur-month">'.$x.'<span class="current-day">'. $today_day .'</span></dt>';
                }
            }
        ?>
    </dl>
</div>
<?php
    echo $customModals;
?>