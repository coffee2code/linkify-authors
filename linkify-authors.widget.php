<?php
/**
 * Linkify Authors plugin widget code
 *
 * Copyright (c) 2011-2024 by Scott Reilly (aka coffee2code)
 *
 * @package Linkify_Authors_Widget
 * @author  Scott Reilly
 * @version 005
 */

defined( 'ABSPATH' ) or die();

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'linkify-widget.php' );

if ( class_exists( 'WP_Widget' ) && ! class_exists( 'c2c_LinkifyAuthorsWidget' ) ) :

class c2c_LinkifyAuthorsWidget extends c2c_LinkifyWidget {

	/**
	 * Returns version of the widget.
	 *
	 * @since 004
	 *
	 * @return string
	 */
	public static function version() {
		return '005';
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$config = array(
			// input can be 'checkbox', 'multiselect', 'select', 'short_text', 'text', 'textarea', 'hidden', or 'none'
			// datatype can be 'array' or 'hash'
			// can also specify input_attributes
			'title' => array(
				'input'   => 'text',
				'default' => __( 'Authors', 'linkify-authors' ),
				'label'   => __( 'Title', 'linkify-authors' ),
			),
			'authors' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Authors', 'linkify-authors' ),
				'help'    => __( 'A single author ID/slug, or multiple author IDs/slugs defined via a comma-separated and/or space-separated string.', 'linkify-authors' ),
			),
			'before' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before text', 'linkify-authors' ),
				'help'    => __( 'Text to display before all authors.', 'linkify-authors' ),
			),
			'after' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'After text', 'linkify-authors' ),
				'help'    => __( 'Text to display after all authors.', 'linkify-authors' ),
			),
			'between' =>  array(
				'input'   => 'text',
				'default' => ', ',
				'label'   => __( 'Between authors', 'linkify-authors' ),
				'help'    => __( 'Text to appear between authors.', 'linkify-authors' ),
			),
			'before_last' =>  array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before last author', 'linkify-authors' ),
				'help'    => __( 'Text to appear between the second-to-last and last element, if not specified, \'between\' value is used.', 'linkify-authors' ),
			),
			'none' =>  array(
				'input'   => 'text',
				'default' => __( 'No authors specified to be displayed', 'linkify-authors' ),
				'label'   => __( 'None text', 'linkify-authors' ),
				'help'    => __( 'Text to appear when no authors have been found.  If blank, then the entire function doesn\'t display anything.', 'linkify-authors' ),
			)
		);

		parent::__construct(
			'linkify_authors',
			__( 'Linkify Authors', 'linkify-authors' ),
			__( 'Converts a list of authors (by slug or ID) into links to those authors.', 'linkify-authors' ),
			$config
		);
	}

	/**
	 * Outputs the main content within the body of the widget.
	 *
	 * @since 005
	 *
	 * @param array $args Widget args.
	 * @param array $instance Widget instance.
	 */
	public function widget_content( $args, $instance ) {
		extract( $args );
		c2c_linkify_authors( $authors, $before, $after, $between, $before_last, $none );
	}

} // end class c2c_LinkifyAuthorsWidget

add_action( 'widgets_init', array( 'c2c_LinkifyAuthorsWidget', 'register_widget' ) );

endif; // end if !class_exists()
