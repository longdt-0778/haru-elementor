<?php
/** 
 * @package    HaruTheme/Haru Elementor
 * @version    1.0.0
 * @author     Administrator <admin@harutheme.com>
 * @copyright  Copyright (c) 2017, HaruTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://harutheme.com
*/

if ( !class_exists( 'Haru_Header_Post_Type' ) ) {
    class Haru_Header_Post_Type {
        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;

        }

        public function __construct(){

            add_action( 'init', array( $this, 'haru_header' ) );

        }
        
        public function haru_header() {
            $labels = array(
                'menu_name'          => esc_html__( 'Header Builders', 'haru-elementor' ),
                'singular_name'      => esc_html__( 'Header', 'haru-elementor' ),
                'name'               => esc_html__( 'All Header', 'haru-elementor' ),
                'add_new'            => esc_html__( 'Add New', 'haru-elementor' ) ,
                'add_new_item'       => esc_html__( 'Add New Header', 'haru-elementor' ) ,
                'edit_item'          => esc_html__( 'Edit Header', 'haru-elementor' ) ,
                'new_item'           => esc_html__( 'Add New Header', 'haru-elementor' ) ,
                'view_item'          => esc_html__( 'View Header', 'haru-elementor' ) ,
                'search_items'       => esc_html__( 'Search Header', 'haru-elementor' ) ,
                'not_found'          => esc_html__( 'No Header items found', 'haru-elementor' ) ,
                'not_found_in_trash' => esc_html__( 'No Header items found in trash', 'haru-elementor' ) ,
                'parent_item_colon'  => ''
            );

            $args = array(
                'labels'                => $labels,
                'description'           => esc_html__( 'Display Header of site', 'haru-pricom' ),
                'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'elementor'),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_icon'             => 'dashicons-menu',
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => false,
                'can_export'            => true,
                'has_archive'           => false,
                'exclude_from_search'   => true,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
            );

            register_post_type( 'haru_header', $args );
        }
    }
}

Haru_Header_Post_Type::instance();
