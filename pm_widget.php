<?php

class Milestones_Widget extends WP_Widget {

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    $widget_ops = array( 
      'classname' => 'milestones_widget',
      'description' => esc_html__( 'New title', 'podemos_milestones' ),
    );
    parent::__construct( 'milestones_widget', 'Milestones Widget', $widget_ops );
  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {

    wp_register_style( 'podemos-milestones', plugins_url( 'podemos-milestones/pm-style.css' ) );
    wp_enqueue_style( 'podemos-milestones' );
  
    if ( ! empty( $instance['title'] ) ) {
      echo '<div class="pretty-box pretty-box-light proximos-hitos">'. $args['before_title'] . '<i class="fa fa-calendar-o"></i>' . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    }

    $today = time() - 12*3600; // 12 hours before now
    $eventsargs = [ 'post_type' => 'milestone', 'order' => 'ASC', 'posts_per_page' => $instance['limit'], 'meta_key'=> 'milestone_starts_at', 'orderby' => 'meta_value', 'exclude' => [ $post->ID ], 'suppress_filters' => false,
                               'meta_query'=> [ 'relation' => 'OR', 
					      [ 'key' => 'milestone_ends_at', 'compare' => '>', 'value' => $today, 'type'=>'numeric' ],
					      [ 'key' => 'milestone_starts_at', 'compare' => '>', 'value' => $today, 'type'=>'numeric' ] ] ];
    $wp_query = new WP_Query($eventsargs);
    $milestones = $wp_query->get_posts();
    foreach($milestones as $milestone) {
	// get post id
	$mid = $milestone->ID;

	// get dates
	$month_start = date_i18n( "M", intval( get_post_meta($mid, "milestone_starts_at", true) ) );
	$month_end = date_i18n( "M", intval( get_post_meta($mid, "milestone_ends_at", true) ) );
	$day_start = date_i18n( "j", intval( get_post_meta($mid, "milestone_starts_at", true) ) ); 
	$day_end = date_i18n( "j", intval( get_post_meta($mid, "milestone_ends_at", true) ) ); 

	// build html
	$pm_widget_html .= '<div class="milestones-widget">';
	$pm_widget_html .= '<div class="milestones-widget-date">';
	$pm_widget_html .= '<div class="pm-date"><div class="pm-oval"><div class="pm-start_day"><div class="pm-month">' . $month_start . '</div>' . $day_start . '</div>';
	// if ends_at is empty
	if(get_post_meta($mid, "milestone_ends_at", true)){
	  $pm_widget_html .= '<div class="pm-end_day">-' . $day_end . '</div>';
	}
	$pm_widget_html .= '</div></div></div>';
	$pm_widget_html .= '<div class="pm_widget-title-container milestones-widget-title"><h3 class="h6">' . get_the_title($milestone) . '</h3></div>';
	$pm_widget_html .= '</div>';
    }
    $pm_widget_html .= '<div class="milestones-widget"></div>';
    $pm_widget_html .= '<div><a class="more_link" href="'.$instance['more_link'].'">'.$instance['more_text'].'</a></div></div>';
    
    echo $pm_widget_html;
  }

  /**
   * Outputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'PRÓXIMOS HITOS', 'podemos_milestones' );
    $limit = ! empty( $instance['limit'] ) ? $instance['limit'] : 2;
    $more_link = ! empty( $instance['more_link'] ) ? $instance['more_link'] : "/calendario/";
    $more_text = ! empty( $instance['more_text'] ) ? $instance['more_text'] : esc_html__( 'Ver calendario completo', 'podemos_milestones' );
    ?>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Titulo:', 'podemos_milestones' ); ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_attr_e( 'Hitos a mostrar:', 'podemos_milestones' ); ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>">
    </p>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>"><?php esc_attr_e( 'Enlace ver más:', 'podemos_milestones' ); ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_link' ) ); ?>" type="text" value="<?php echo esc_attr( $more_link ); ?>">
    </p>
    <p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_attr_e( 'Texto ver más:', 'podemos_milestones' ); ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>">
    </p>
    <?php 
  }

  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '1';
    $instance['more_link'] = ( ! empty( $new_instance['more_link'] ) ) ? strip_tags( $new_instance['more_link'] ) : '';
    $instance['more_text'] = ( ! empty( $new_instance['more_text'] ) ) ? strip_tags( $new_instance['more_text'] ) : '';

    return $instance;
  }
}

add_action( 'widgets_init', function(){
  register_widget( 'Milestones_Widget' );
});
