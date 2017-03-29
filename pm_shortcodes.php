<?php

function pm_milestone_list( $atts ){
  wp_register_style( 'podemos-milestones', plugins_url( 'podemos-milestones/pm-style.css' ) );
  wp_enqueue_style( 'podemos-milestones' );
  wp_register_script( 'podemos-milestones', plugins_url( 'podemos-milestones/pm.js' ) );
  wp_enqueue_script( 'podemos-milestones' );
  $wp_query = new WP_Query(array( 'post_type' => 'milestone',
				  'posts_per_page' => 50,
				  'orderby' => 'meta_value', 
				  'meta_key' => 'milestone_starts_at',
                                  'suppress_filters' => false,
				  'order' => 'ASC'));
  if ( $wp_query->have_posts() ) {
    $milestones = $wp_query->get_posts();
    $pm_html .= '<div class="container">';
    $pm_html .= ($atts["title"]) ? ('  <h2 style="color:#683064;">'.$atts["title"].'</h2>'):('');
    $pm_html .= '<div class="accordion" id="milestones">';
    $pm_html .= '<div class="milestone-group">';
    $pm_html .= '<div class="panel milestone-default">';
    foreach($milestones as $milestone) {
	$mid = $milestone->ID;
	$month_start = date_i18n( "M", intval( get_post_meta($mid, "milestone_starts_at", true) ) );
	$month_end = date_i18n( "M", intval( get_post_meta($mid, "milestone_ends_at", true) ) );
	$day_start = date_i18n( "j", intval( get_post_meta($mid, "milestone_starts_at", true) ) ); 
	$day_end = date_i18n( "j", intval( get_post_meta($mid, "milestone_ends_at", true) ) );
	$the_content = apply_filters('the_content', get_post_field('post_content', $mid));
	$pm_html .= '<div class="accordion-toggle accordion-heading milestone-heading'.(($the_content)?(' milestone-heading-pointer"'):('')).' data-toggle="collapse" data-target="#desc-' . $mid . '" data-parent="#milestones">';
	$pm_html .= '<div class="pm-date"><div class="pm-oval"><div class="pm-start_day"><div class="pm-month">' . $month_start . '</div>' . $day_start . '</div>';
	if(get_post_meta($mid, "milestone_ends_at", true)){
	  $pm_html .= '<div class="pm-end_day">-' . $day_end . '</div>';
	}
	$pm_html .= '</div></div>';
	$pm_html .= '<div class="title-container"><h4 class="h6 milestone-title">'. get_the_title($milestone) . '</h4></div>';
	$pm_html .= $the_content ? '<i class="accordion-heading-fa-i fa fa-angle-down" aria-hidden="true"></i>' : '<i class="accordion-heading-fa-i fa fa-angle-down invisible" aria-hidden="true"></i>';
	$pm_html .= '</div>';
	$pm_html .= $the_content ? '<div class="milestone-collapse collapse" id="desc-' . $mid . '"><div class="milestone-body">' . $the_content . '</div></div>' : '';
	wp_reset_postdata();
    }
    $pm_html .= '<div class="accordion-heading"></div></div></div></div></div>';
  }
  return $pm_html;
}

// register shortcode
add_shortcode( 'milestones', 'pm_milestone_list' );
