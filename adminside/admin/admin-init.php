<?php

    // Load the TGM init if it exists
    //if ( !class_exists( 'TGM_Plugin_Activation' ) ) {
      //  require_once dirname( __FILE__ ) . '/../thetgm/class-tgm-plugin-activation.php';
//		require_once( dirname( __FILE__ ) . '/tgm-init.php' );
  //  }

    // Load the embedded Redux Framework
    if ( file_exists( dirname( __FILE__ ).'/ReduxFramework/framework.php' ) ) {
        require_once dirname(__FILE__).'/ReduxFramework/framework.php';
    }
	
    
	if( 0 ) {
		// Load the embedded Redux Framework
		if ( file_exists( dirname( __FILE__ ).'/sample/sample-config.php' ) ) {
			require_once dirname(__FILE__).'/sample/sample-config.php';
		}
	}
	else {
		// Load the theme/plugin options
		if ( file_exists( dirname( __FILE__ ) . '/options-init.php' ) ) {
			require_once dirname( __FILE__ ) . '/options-init.php';
		}
	}

    // Load Redux extensions
    if ( file_exists( dirname( __FILE__ ) . '/redux-extensions/extensions-init.php' ) ) {
        require_once dirname( __FILE__ ) . '/redux-extensions/extensions-init.php';
    }
	
?>