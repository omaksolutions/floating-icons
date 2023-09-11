<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
    if ( ! class_exists( 'Redux' ) ) {
      // Delete tgmpa dismiss flag
      delete_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_myarcadetheme' );
      return;
    }

    /** remove redux menu under the tools **/
    function omfe_remove_redux_menu() {
      remove_submenu_page('tools.php','redux-about');
    }
    add_action( 'admin_menu', 'omfe_remove_redux_menu', 12 );

    // Deactivate News Flash
    $GLOBALS['redux_notice_check'] = 0;

    // This is your option name where all the Redux data is stored.
    $opt_name = "omfe_opts";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); //For use with some settings. Not necessary.
    require_once(ABSPATH.'wp-admin/includes/plugin.php');   
    $plugin = get_plugin_data( plugin_dir_path( __FILE__ ) .'../../floating-icons.php' );

    $args = array(
        'opt_name' => 'omfe_opts',
        'dev_mode' => false,
        'ajax_save' => true,
        'allow_tracking' => false,
        'tour' => false,  
        'use_cdn' => true,
        'display_name' => $plugin['Name'],
        'display_version' => $plugin['Version'],
        'page_slug' => 'floating_icons',
        'page_title' => $plugin['Name'] . ' Options',
        'intro_text' => $plugin['Description'],
        'admin_bar' => false,
        'menu_type' => 'menu',
        'menu_title' => 'Floating Icons',
        'menu_icon' => 'dashicons-heart',
        'allow_sub_menu' => false,
        'page_parent' => 'index.php',
        'page_parent_post_type' => '',
        'default_show' => TRUE,
        'default_mark' => '*',
        'class' => 'omfe_container',
        
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => FALSE,
        'show_options_object' => FALSE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
        'hide_reset' => TRUE,
        'footer_credit' => 'Floating Icons by Om Ak Solutions',
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'] = array(); 
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/OmAkSolutions',
        'title' => __('Like us on Facebook', 'floating-icons' ),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/singlaAk',
        'title' => __('Follow us on Twitter', 'floating-icons' ),
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/Om-Ak-Solutions',
        'title' => __('Find us on LinkedIn', 'floating-icons' ),
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '', 'floating-icons' ), $v );
    } else {
        $args['intro_text'] = __( '', 'floating-icons' );
    }
    
    // Intro Text Emptied
    $args['intro_text'] = sprintf( __( '', 'floating-icons' ), $v );    
    
    Redux::set_args( $opt_name, $args );

    $icons_list = omfe_get_icons_list(); 

    /////////////////////////////////////////////////
    // SECTION: Settings
    /////////////////////////////////////////////////
    if ( 1 ) {
        Redux::set_section( $opt_name, array(
            'title'  => __( 'Settings', 'floating-icons' ),
            'id'     => 'settings',
            'desc'   => __( '', 'floating-icons' ),
            'icon'   => 'el el-cogs',
            'fields' => array(
                array(
                    'id'            => 'where_to_show',
                    'type'          => 'select',
                    'title'         => __( 'Where to show the Icons?', 'floating-icons' ),
                    'subtitle'      => __( 'Choose from everywhere, on Selected Pages and Not on Selected Pages.', 'floating-icons' ),
                    'options'  => array(
                        'everywhere' => 'Everywhere',
                        'onselected' => 'Only Selected Pages',
                        'notonselected' => __('Not On Selected Pages', 'floating-icons' ),
                    ),
                    'default'  => 'everywhere',
                    'hint'      => array(
						'title'     => __( 'Where to show the Icons', 'floating-icons' ),
						'content'   => __( 'Choose on which utilites you want to show the Icons either Pages, Posts, Categories, Tags etc.', 'floating-icons' ),
					),
                ),
                array(
                    'id'            => 'choose_pages',
                    'type'          => 'select',
                    'multi'          => true,
                    'data'          => 'pages',
                    'args'          => array( 'posts_per_page' => -1),
                    'required'      => array('where_to_show', '!=', 'everywhere'),
                    'title'         => __( 'Choose Your Pages', 'floating-icons' ),
                    'subtitle'      => __( 'Select the pages to exclude or include for Icons form display.', 'floating-icons' ),
                ),
                array(
                    'id'       => 'iconzone',
                    'type'     => 'select',
                    'title'    => __( 'Zone', 'floating-icons' ),
                    'subtitle' => __( 'Select display zone for Icons.', 'floating-icons' ),
                    'options'  => array(
                        '1' => 'All over the page',
                        '2' => 'Specific Element',                      
                    ),
                    'default'  => '1',
                    'hint'      => array(
						'title'     => __( 'Select display zone for Icons', 'floating-icons' ),
						'content'   => __( 'Choose from either All over the page or Specific Element.', 'floating-icons' ),
					),
                ), 
                array(
                    'id'       => 'iconarea-class',
                    'type'     => 'text',            
                    'title'    => __( 'Element Selector', 'floating-icons' ),
                    'subtitle' => __( 'Specify # with id, . with classname', 'floating-icons' ),
                    'required' => array('iconzone', '=', '2'),
                    'default'  => '',
                    'hint'      => array(
						'title'     => __( 'On which element you want to show', 'floating-icons' ),
						'content'   => __( 'Specify # with id, . with classname', 'floating-icons' ),
					),
                ),
                array(
                    'id'       => 'icon',
                    'type'     => 'select',
                    'title'    => __( 'Choose an Icon', 'floating-icons' ),
                    'subtitle' => __( 'Choose the desired icon', 'floating-icons' ),
                    'desc'     => __( '', 'floating-icons' ),
                    'options'  => $icons_list, 
                    'default'  => 'fa fa-heart',
                    'hint'      => array(
						'title'     => __( 'Choose the Icons', 'floating-icons' ),
						'content'   => __( 'Choose from either Hearts, Stars, Asterisk, Trees or Drops.', 'floating-icons' ),
					),
                ),
                array(
                    'id'            => 'iconcolor-1',
                    'type'          => 'color',         
                    'title'         => __( 'First Icon color', 'floating-icons' ),
                    'subtitle'      => __( 'Color of the Icons to be displayed.', 'floating-icons' ),
                    'desc'          => __( '', 'floating-icons' ),
                    'default'       => '#ff0000',
                    'transparent'   => false,
                    'hint'      => array(
						'title'     => __( 'Choose color 1', 'floating-icons' ),
						'content'   => __( 'Choose from all the color given.', 'floating-icons' ),
					),
                ),
                array(
                    'id'            => 'iconcolor-2',
                    'type'          => 'color',         
                    'title'         => __( 'Second Icon color', 'floating-icons' ),
                    'subtitle'      => __( 'Color of the Icons to be displayed.', 'floating-icons' ),
                    'desc'          => __( '', 'floating-icons' ),
                    'default'       => '#c63b3b',
                    'transparent'   => false,
                    'hint'      => array(
						'title'     => __( 'Choose color 2', 'floating-icons' ),
						'content'   => __( 'Choose from all the color given.', 'floating-icons' ),
					),
                ),
                array(
                    'id'            => 'iconcolor-3',
                    'type'          => 'color',         
                    'title'         => __( 'Third Icon color', 'floating-icons' ),
                    'subtitle'      => __( 'Color of the Icons to be displayed.', 'floating-icons' ),
                    'desc'          => __( '', 'floating-icons' ),
                    'default'       => '#ff8e8e',
                    'transparent'   => false,
                    'hint'      => array(
						'title'     => __( 'Choose color 3', 'floating-icons' ),
						'content'   => __( 'Choose from all the color given.', 'floating-icons' ),
					),
                ),
                array(
                    'id'       	=> 'iconmax',
                    'type' 		=> 'slider',
				    'title'    	=> __( 'Number of Icons', 'floating-icons' ),
                    'subtitle' 	=> __( 'Maximum no. of Icons.', 'floating-icons' ),
				    'default'   => 20,
				    'min'       => 15,
				    'step'      => 5,
				    'max'       => 200,
				    'display_value' => 'text',
				    'hint'      => array(
						'title'     => __( 'Number of Icons', 'floating-icons' ),
						'content'   => __( 'Select the number of Icons.', 'floating-icons' ),
					),
                ),
                array(
                    'id'       	=> 'sinking-speed',
				    'type' 		=> 'slider',
				    'title'    	=> __( 'Sinking Speed', 'floating-icons' ),
                    'subtitle' 	=> __( 'Sinking speed of the Icons.', 'floating-icons' ),
                    'desc'     	=> __( 'recommended values range from 0.3-2.0', 'floating-icons' ),
				    "default" 	=> .5,
				    "min" 		=> 0.5	,
				    "step" 		=> .1,
				    "max" 		=> 3,
				    'resolution' => 0.1,
				    'display_value' => 'text',
				    'hint'      => array(
						'title'     => __( 'Choose the Sinking Speed of the Icons', 'floating-icons' ),
						'content'   => __( 'This options will give the speed by which the icons move across your screen.', 'floating-icons' ),
					),
                ),
                array(
                    'id'       	=> 'maxsize',
                    'type'      => 'slider',
				   	'title'    	=> __( 'Maximum size', 'floating-icons' ),
                    'subtitle' 	=> __( 'Maximum font size of Icons.', 'floating-icons' ),
				    'default'   => 12,
				    'min'       => 9,
				    'step'      => 1,
				    'max'       => 20,
				    'display_value' => 'text',
				    'hint'      => array(
						'title'     => __( 'Choose Maximum size of your Icons', 'floating-icons' ),
						'content'   => __( 'It is adviced that you dont go too big with the icon size as it may overlap with the content of your website.', 'floating-icons' ),
					),
                ),
                array(
                    'id'       => 'minsize',
                    'type'      => 'slider',
				   	'title'    => __( 'Minimum size', 'floating-icons' ),
                    'subtitle' => __( 'Minimum font size of Icons.', 'floating-icons' ),
				    'default'   => 6,
				    'min'       => 4,
				    'step'      => 1,
				    'max'       => 9,
				    'display_value' => 'text',
				    'hint'      => array(
						'title'     => __( 'Choose Minimum size of your Icons', 'floating-icons' ),
						'content'   => __( 'You should keep atleast 4-5 px difference between the minimum and maximum size of the Icons.', 'floating-icons' ),
					),
                ),
                array(
				    'id'    => 'email_notice',
				    'type'  => 'info',
				    'icon'  => 'el-icon-info-sign',
				    'style' => 'warning',
				    'title' => __('Still getting Confused', 'floating-icons'),
				    'desc'  => __('For support regarding this plugin contact us at poke@slickpopup.com', 'floating-icons')
				),
            )
        ) );
    } // endif 1