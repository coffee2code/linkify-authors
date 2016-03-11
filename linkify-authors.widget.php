<?php
/**
 * Linkify Authors plugin widget code
 *
 * Copyright (c) 2011-2016 by Scott Reilly (aka coffee2code)
 *
 * @package Linkify_Authors_Widget
 * @author  Scott Reilly
 * @version 004
 */

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Widget' ) && ! class_exists( 'c2c_LinkifyAuthorsWidget' ) ) :

class c2c_LinkifyAuthorsWidget extends WP_Widget {

	/**
	 * Widget ID.
	 *
	 * @access private
	 * @var    string
	 */
	private $widget_id = 'linkify_authors';

	/**
	 * Widget title.
	 *
	 * @access private
	 * @var    string
	 */
	private $title = '';

	/**
	 * Widget description.
	 *
	 * @access private
	 * @var    string
	 */
	private $description = '';

	/**
	 * Widget configuration.
	 *
	 * @access private
	 * @var    array
	 */
	private $config = array();

	/**
	 * Widget default configuration.
	 *
	 * @access private
	 * @var    array
	 */
	private $defaults = array();

	/**
	 * Registers the widget.
	 *
	 * @since 004
	 */
	public static function register_widget() {
		register_widget( __CLASS__ );
	}

	/**
	 * Returns the version of the widget.
	 *
	 * @since 004
	 */
	public static function version() {
		return '004';
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->title = __( 'Linkify Authors', 'linkify-authors' );

		$this->config = array(
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
				'default' => '',
				'label'   => __( 'None text', 'linkify-authors' ),
				'help'    => __( 'Text to appear when no authors have been found.  If blank, then the entire function doesn\'t display anything.', 'linkify-authors' ),
			)
		);

		foreach ( $this->config as $key => $value ) {
			$this->defaults[ $key ] = $value['default'];
		}
		$widget_ops = array(
			'classname'   => 'widget_' . $this->widget_id,
			'description' => __( 'Converts a list of authors (by slug or ID) into links to those authors.', 'linkify-authors' ),
		);
		$control_ops = array(); //array( 'width' => 400, 'height' => 350, 'id_base' => $this->widget_id );
		parent::__construct( $this->widget_id, $this->title, $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		/* Settings */
		foreach ( array_keys( $this->config ) as $key ) {
			$$key = apply_filters( 'linkify_authors_widget_'.$key, $instance[ $key ] );
		}

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Widget content
		c2c_linkify_authors( $authors, $before, $after, $between, $before_last, $none );

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $key => $value ) {
			$instance[ $key ] = $value;
		}

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$i = $j = 0;
		foreach ( $instance as $opt => $value ) {
			if ( $opt == 'submit' ) {
				continue;
			}

			foreach ( array( 'datatype', 'default', 'help', 'input', 'input_attributes', 'label', 'no_wrap', 'options' ) as $attrib ) {
				if ( ! isset( $this->config[ $opt ][ $attrib ] ) ) {
					$this->config[ $opt ][ $attrib ] = '';
				}
			}

			$input = $this->config[ $opt ]['input'];
			$label = $this->config[ $opt ]['label'];

			if ( 'none' == $input ) {
				if ( 'more' == $opt ) {
					$i++; $j++;
					echo "<p>$label</p>";
					echo "<div class='widget-group widget-group-$i'>";
				} elseif ( 'endmore' == $opt ) {
					$j--;
					echo '</div>';
				}
				continue;
			}

			if ( 'checkbox' == $input ) {
				$checked = checked( $value, 1, false );
				$value = 1;
			} else {
				$checked = '';
			};

			if ( 'multiselect' == $input ) {
				// Do nothing since it needs the values as an array
			} elseif ( 'array' == $this->config[ $opt ]['datatype'] ) {
				if ( ! is_array( $value ) ) {
					$value = '';
				} else {
					$value = implode( ('textarea' == $input ? "\n" : ', '), $value );
				}
			} elseif ( 'hash' == $this->config[ $opt ]['datatype'] ) {
				if ( ! is_array( $value ) ) {
					$value = '';
				} else {
					$new_value = '';
					foreach ( $value AS $shortcut => $replacement ) {
						$new_value .= "$shortcut => $replacement\n";
					}
					$value = $new_value;
				}
			}

			echo "<p>";

			$input_id   = $this->get_field_id( $opt );
			$input_name = $this->get_field_name( $opt );

			if ( $label && ( 'multiselect' != $input ) ) {
				printf(
					"<label for='%s'>%s:</label> ",
					esc_attr( $input_id ),
					$label
				);
			}

			if ( 'textarea' == $input ) {
				printf(
					"<textarea name='%s' id='%s' class='widefat' %s>%s</textarea>",
					esc_attr( $input_name ),
					esc_attr( $input_id ),
					$this->config[ $opt ]['input_attributes'],
					$value
				);
			} elseif ( 'select' == $input ) {
				printf(
					"<select name='%s' id='%s'>",
					esc_attr( $input_name ),
					esc_attr( $input_id )
				);
				foreach ( (array) $this->config[ $opt ]['options'] as $sopt ) {
					printf(
						"<option value='%s'%s>%s</option>",
						esc_attr( $sopt ),
						selected( $sopt, $value, false ),
						$sopt
					);
				}
				echo "</select>";
			} elseif ( 'multiselect' == $input ) {
				echo '<fieldset style="border:1px solid #ccc; padding:2px 8px;">';
				if ( $label ) {
					echo "<legend>$label: </legend>";
				}
				foreach ( (array) $this->config[ $opt ]['options'] as $sopt ) {
					printf(
						"<input type='checkbox' name='%s' id='%s' value='%s'%s>%s</input><br />",
						esc_attr( $input_name ),
						esc_attr( $input_id ),
						esc_attr( $sopt ),
						checked( in_array( $sopt, $value ), true, false ),
						$sopt
					);
				}
				echo '</fieldset>';
			} elseif ( $input ) { // If no input defined, then not valid input
				if ( 'short_text' == $input ) {
					$tclass = '';
					$tstyle = 'width:25px;';
					$input = 'text';
				} else {
					$tclass = 'widefat';
					$tstyle = '';
				}
				printf(
					"<input name='%s' type='%s' id='%s' value='%s' class='%s' style='%s' %s %s />",
					esc_attr( $input_name ),
					esc_attr( $input ),
					esc_attr( $input_id ),
					esc_attr( $value ),
					esc_attr( $tclass ),
					esc_attr( $tstyle ),
					$checked,
					$this->config[ $opt ]['input_attributes']
				);
			}
			if ( $this->config[ $opt ]['help'] ) {
				echo "<br /><span style='color:#888; font-size:x-small;'>({$this->config[ $opt ]['help']})</span>";
			}
			echo "</p>\n";
		}
		// Close any open divs
		for ( ; $j > 0; $j-- ) { echo '</div>'; }
	}

} // end class c2c_LinkifyAuthorsWidget

add_action( 'widgets_init', array( 'c2c_LinkifyAuthorsWidget', 'register_widget' ) );

endif; // end if !class_exists()
