<?php

function add_milestone_custom_type() {
  $supports = [ 'title', 'editor' ];
  if(function_exists("register_field_group"))
  {
    register_field_group( [
      'id' => 'acf_hitos',
      'title' => 'Hitos',
      'fields' => [
        [ 'key' => 'milestone_starts_at', 'label' => 'Fecha y hora de inicio', 'name' => 'milestone_starts_at', 'type' => 'date_time_picker' ], 
        [ 'key' => 'milestone_ends_at', 'label' => 'Fecha y hora de fin', 'name' => 'milestone_ends_at', 'type' => 'date_time_picker' ],
      ],
      'location' => [[[ 'param' => 'post_type', 'operator' => '==', 'value' => 'milestone', 'order_no' => 0, 'group_no' => 0 ]]],
      'options' => [ 'position' => 'normal', 'layout' => 'no_box', 'hide_on_screen' => []],
      'menu_order' => 0,
    ]);
  } else {
    $supports []= "custom-fields";
  }
  
  register_post_type('milestone', [
    'labels' => [
        'name' => __('Hitos', 'podemos-milestones'),
        'singular_name' => __('Hito', 'podemos-milestones'),
        'add_new' => __('Añadir nuevo', 'podemos-milestones'),
        'add_new_item' => __('Añadir nuevo hito', 'podemos-milestones'),
        'edit' => __('Editar', 'podemos-milestones'),
        'edit_item' => __('Editar hito', 'podemos-milestones'),
        'new_item' => __('Nuevo hito', 'podemos-milestones'),
        'view' => __('Ver', 'podemos-milestones'),
        'view_item' => __('Ver hito', 'podemos-milestones'),
        'search_items' => __('Buscar hito', 'podemos-milestones'),
        'not_found' => __('No se han encontrado hitos', 'podemos-milestones'),
        'not_found_in_trash' => __('No se han encontrado hitos en la papelera', 'podemos-milestones')
    ],
    'public' => true,
    'hierarchical' => false,
    'has_archive' => true,
    'supports' => $supports,
    'can_export' => true,
    'taxonomies' => [  ], 
    'rewrite' => [ 'slug' => 'hitos', 'with_front' => false ],
    'menu_icon' => 'dashicons-calendar-alt',
    'menu_position' => 20	
  ]);
}

add_action( 'init' , 'add_milestone_custom_type' );
