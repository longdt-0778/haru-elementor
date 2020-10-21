<?php
/** 
 * @package    HaruTheme/Haru Elementor
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Haru_Elementor_Logo_Widget' ) ) {
	class Haru_Elementor_Logo_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'haru-logo';
		}

		public function get_title() {
			return esc_html__( 'Haru Header Logo', 'haru-elementor' );
		}

		public function get_icon() {
			return 'fa fa-code';
		}

		public function get_categories() {
			return [ 'haru-header-elements' ];
		}

		protected function _register_controls() {

			$this->start_controls_section(
	            'content_section',
	            [
	                'label' => esc_html__( 'Content', 'haru-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	            ]
	        );

	        $this->add_control(
	            'logo',
	            [
	                'label' => esc_html__( 'Choose Logo', 'haru-elementor' ),
	                'type' => \Elementor\Controls_Manager::MEDIA,
	                'dynamic' => [
	                    'active' => true,
	                ],
	                'default' => [
	                    'url' => \Elementor\Utils::get_placeholder_image_src(),
	                ],
	            ]
	        );

	        $this->add_control(
	            'logo_retina',
	            [
	                'label' => esc_html__( 'Choose Logo Retina', 'haru-elementor' ),
	                'type' => \Elementor\Controls_Manager::MEDIA,
	                'dynamic' => [
	                    'active' => true,
	                ],
	                'default' => [
	                    'url' => \Elementor\Utils::get_placeholder_image_src(),
	                ],
	            ]
	        );

	        $this->add_control(
				'max_height',
				[
					'label' => __( 'Logo Max Height', 'haru-elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 40,
				]
			);

	        $this->add_control(
	            'el_class',
	            [
	                'label'         => esc_html__( 'Extra Class', 'haru-elementor' ),
	                'type'          => \Elementor\Controls_Manager::TEXT,
	                'placeholder'   => esc_html__( 'Add extra class for Element and use custom CSS for get different style.', 'haru-elementor' ),
	            ]
	        );

	        $this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings();

        	extract( $settings );
        	?>

        	<div class="logo <?php echo esc_attr( $el_class ); ?>">
        		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        			<img src="<?php echo esc_url( $settings['logo']['url'] ); ?> '" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" style="max-height: <?php echo esc_attr( $settings['max_height'] ); ?>px;">
    			</a>
    		</div>

    		<?php
		}

		protected function _content_template() {
			?>
			<!-- <img src="{{ settings.logo.url }}"> -->
			<?php
		}

	}
}
