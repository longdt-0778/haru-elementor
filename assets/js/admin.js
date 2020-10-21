/**
 * @package    HaruTheme
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

var HARUELEMENTOR = HARUELEMENTOR || {};

(function ($) {
	// Base functions
    HARUELEMENTOR.base = {
    	init: function() {
            HARUELEMENTOR.base.elementorAccordion();
        },
        elementorAccordion: function() {
        	alert('test');
        	var delay = 10; 

        	setTimeout( function() { 
				$('.elementor-tab-title').removeClass( 'elementor-active' );
			 	$('.elementor-tab-content').css( 'display', 'none' ); 
			}, delay );
        }
    }

    // Document ready
    HARUELEMENTOR.onReady = {
        init: function () {
            HARU.base.init();
        }
    };

    $(document).ready( HARUELEMENTOR.onReady.init );
})(jQuery);
