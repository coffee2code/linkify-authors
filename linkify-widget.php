<?php
/**
 * Linkify plugin widget code
 *
 * Copyright (c) 2011-2024 by Scott Reilly (aka coffee2code)
 *
 * @package Linkify_Widget
 * @author  Scott Reilly
 * @version 005
 */

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Widget' ) && ! class_exists( 'c2c_LinkifyWidget' ) ) :

abstract class c2c_LinkifyWidget extends WP_Widget {

	abstract function widget_content( $args, $instance );

	/**
	 * Widget ID.
	 *
	 * @access private
	 * @var    string
	 */
	private $widget_id = '';

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
		register_widget( get_called_class() );
	}

	/**
	 * Returns the version of the widget.
	 *
	 * @since 004
	 */
	public static function version() {
		return '005';
	}

	/**
	 * Constructor.
	 */
	public function __construct( $id, $title, $description, $config ) {
		$this->widget_id = $id;
		$this->title     = $title;
		$this->config    = $config;

		foreach ( $this->config as $key => $value ) {
			$this->defaults[ $key ] = $value['default'];
		}

		$widget_ops = array(
			'classname'   => 'widget_' . $this->widget_id,
			'description' => $description,
		);
		$control_ops = array(); //array( 'width' => 400, 'height' => 350, 'id_base' => $this->widget_id );
		parent::__construct( $this->widget_id, $this->title, $widget_ops, $control_ops );
	}

	/**
	 * Returns the config array.
	 *
	 * @since 005
	 *
	 * @return array
	 */
	public function get_config() {
		return $this->config;
	}

	/**
	 * Outputs the body of the widget.
	 *
	 * @param array $args Widget args.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		/* Settings */
		foreach ( array_keys( $this->config ) as $key ) {
			$$key = apply_filters( $this->widget_id . '_widget_' . $key, $instance[ $key ] );
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $before_widget;

		if ( $title ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $before_title . $title . $after_title;
		}

		// Widget content
		$args = compact( array_keys( $this->config ) );
		$this->widget_content( $args, $instance );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $key => $value ) {
			$instance[ $key ] = $value;
		}

		return $instance;
	}

	/**
	 * Returns escaped attributes string for an HTML tag.
	 *
	 * @since 005
	 *
	 * @param string[] $attributes Associative array of attribute names and values.
	 * @return string
	 */
	public function esc_attributes( $attributes ) {
		$string = '';

		foreach ( $attributes as $key => $value ) {
			$string .= sprintf(
				'%s="%s" ',
				esc_attr( wp_strip_all_tags( $key ) ),
				esc_html( wp_strip_all_tags( $value ) )
			);
		}

		return trim( $string );
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
					echo '<p>' . esc_html( $label ) . '</p>';
					printf ( '<div class="widget-group widget-group-%d">', intval( $i ) );
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
					esc_html( $label )
				);
			}

			if ( 'textarea' == $input ) {
				printf(
					"<textarea name='%s' id='%s' class='widefat' %s>%s</textarea>",
					esc_attr( $input_name ),
					esc_attr( $input_id ),
					// PHPCS: The keys and values of all attributes are being escaped by esc_attributes(), so this is safe.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$this->esc_attributes( $this->config[ $opt ]['input_attributes'] ),
					esc_html( $value )
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
						esc_html( $sopt )
					);
				}
				echo "</select>";
			} elseif ( 'multiselect' == $input ) {
				echo '<fieldset style="border:1px solid #ccc; padding:2px 8px;">';
				if ( $label ) {
					echo '<legend>' . esc_html( $label ) . ': </legend>';
				}
				foreach ( (array) $this->config[ $opt ]['options'] as $sopt ) {
					printf(
						"<input type='checkbox' name='%s' id='%s' value='%s'%s>%s</input><br />",
						esc_attr( $input_name ),
						esc_attr( $input_id ),
						esc_attr( $sopt ),
						checked( in_array( $sopt, $value ), true, false ),
						esc_html( $sopt )
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
					( 'checkbox' === $input ? checked( $value, 1, false ) : '' ),
					// PHPCS: The keys and values of all attributes are being escaped by esc_attributes(), so this is safe.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$this->esc_attributes( $this->config[ $opt ]['input_attributes'] )
				);
			}
			if ( $this->config[ $opt ]['help'] ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo "<div style='color:#888; font-size:x-small;'>({$this->config[ $opt ]['help']})</div>";
			}
			echo "</p>\n";
		}
		// Close any open divs
		for ( ; $j > 0; $j-- ) { echo '</div>'; }
	}

} // end class c2c_LinkifyWidget

endif; // end if !class_exists()
