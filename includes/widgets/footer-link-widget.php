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

if ( ! class_exists( 'Haru_Elementor_Footer_Link_Widget' ) ) {
	class Haru_Elementor_Footer_Link_Widget extends \Elementor\Widget_Base {

		public function get_name() {
			return 'haru-footer-link';
		}

		public function get_title() {
			return esc_html__( 'Haru Footer Link', 'haru-elementor' );
		}

		public function get_icon() {
			return 'fa fa-code';
		}

		public function get_categories() {
			return [ 'haru-footer-elements' ];
		}

		protected function _register_controls() {

			$this->start_controls_section(
	            'content_section',
	            [
	                'label' => esc_html__( 'Content', 'haru-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	            ]
	        );

	        $repeater = new \Elementor\Repeater();

	        $repeater->add_control(
				'list_title', [
					'label' => esc_html__( 'Title', 'haru-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'List Title' , 'haru-elementor' ),
					'label_block' => true,
				]
			);

			$repeater->add_control(
				'list_content', [
					'label' => esc_html__( 'Link', 'haru-elementor' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'haru-elementor' ),
					'show_external' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
						'nofollow' => true,
					],
				]
			);

			$repeater->add_control(
				'list_color',
				[
					'label' => esc_html__( 'Color', 'haru-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
					],
				]
			);

			$this->add_control(
				'list',
				[
					'label' => esc_html__( 'Link List', 'haru-elementor' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'list_title' => esc_html__( 'Title #1', 'haru-elementor' ),
							'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'haru-elementor' ),
						],
						[
							'list_title' => esc_html__( 'Title #2', 'haru-elementor' ),
							'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'haru-elementor' ),
						],
					],
					'title_field' => '{{{ list_title }}}',
				]
			);

	        $this->add_control(
	            'el_class',
	            [
	                'label'         => esc_html__( 'Extra Class Name', 'haru-elementor' ),
	                'type'          => \Elementor\Controls_Manager::TEXT,
	                'de'   => esc_html__( 'Add extra class for Element and use custom CSS for get different style.', 'haru-elementor' ),
	            ]
	        );

	        $this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings();

        	extract( $settings );
        	?>

        	<div class="logo <?php echo esc_attr( $el_class ); ?>">
        		<?php
        			if ( $settings['list'] ) {
						echo '<dl>';
						foreach (  $settings['list'] as $item ) {
							echo '<dt class="elementor-repeater-item-' . $item['_id'] . '">' . $item['list_title'] . '</dt>';
							echo '<dd>' . $item['list_content'] . '</dd>';
						}
						echo '</dl>';
					}
        		?>
    		</div>

    		<?php
		}

		protected function _content_template() {
			?>
			<?php
		}

	}
}
