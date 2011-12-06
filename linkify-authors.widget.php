<?php
/**
 * @package Linkify_Authors_Widget
 * @author Scott Reilly
 * @version 001
 */
/*
 * Linkify Authors plugin widget code
 *
 * Copyright (c) 2011-2012 by Scott Reilly (aka coffee2code)
 *
 */

if ( class_exists( 'WP_Widget' ) && !class_exists( 'c2c_LinkifyAuthorsWidget' ) ) :

class c2c_LinkifyAuthorsWidget extends WP_Widget {
	private $widget_id = 'linkify_authors';
	private $textdomain = 'linkify-authors';
	private $title = '';
	private $description = '';
	private $config = array();
	private $defaults = array();

	public function c2c_LinkifyAuthorsWidget() {
		$this->title = __( 'Linkify Authors', $this->textdomain );

		$this->config = array(
			// input can be 'checkbox', 'multiselect', 'select', 'short_text', 'text', 'textarea', 'hidden', or 'none'
			// datatype can be 'array' or 'hash'
			// can also specify input_attributes
			'title' => array( 'input' => 'text', 'default' => __( 'Authors', $this->textdomain ),
					'label' => __( 'Title', $this->textdomain ) ),
			'authors' => array( 'input' => 'text', 'default' => '',
					'label' => __( 'Authors', $this->textdomain ),
					'help' => __( 'A single author ID/slug, or multiple author IDs/slugs defined via a comma-separated and/or space-separated string.', $this->textdomain ) ),
			'before' => array( 'input' => 'text', 'default' => '',
					'label' => __( 'Before text', $this->textdomain ),
					'help' => __( 'Text to display before all authors.', $this->textdomain ) ),
			'after' => array( 'input' => 'text', 'default' => '',
					'label' => __( 'After text', $this->textdomain ),
					'help' => __( 'Text to display after all authors.', $this->textdomain ) ),
			'between' =>  array( 'input' => 'text', 'default' => ', ',
					'label' => __( 'Between authors', $this->textdomain ),
					'help' => __( 'Text to appear between authors.', $this->textdomain ) ),
			'before_last' =>  array( 'input' => 'text', 'default' => '',
					'label' => __( 'Before last author', $this->textdomain ),
					'help' => __( 'Text to appear between the second-to-last and last element, if not specified, \'between\' value is used.', $this->textdomain ) ),
			'none' =>  array( 'input' => 'text', 'default' => '',
					'label' => __( 'None text', $this->textdomain ),
					'help' => __( 'Text to appear when no authors have been found.  If blank, then the entire function doesn\'t display anything.', $this->textdomain ) )
		);

		foreach ( $this->config as $key => $value )
			$this->defaults[$key] = $value['default'];
		$widget_ops = array( 'classname' => 'widget_' . $this->widget_id, 'description' => __( 'Converts a list of authors (by slug or ID) into links to those authors.', $this->textdomain ) );
		$control_ops = array(); //array( 'width' => 400, 'height' => 350, 'id_base' => $this->widget_id );
		$this->WP_Widget( $this->widget_id, $this->title, $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		/* Settings */
		foreach ( array_keys( $this->config ) as $key )
			$$key = apply_filters( 'linkify_authors_widget_'.$key, $instance[$key] );

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Widget content
		c2c_linkify_authors( $authors, $before, $after, $between, $before_last, $none );

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $key => $value )
			$instance[$key] = $value;
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$i = $j = 0;
		foreach ( $instance as $opt => $value ) {
			if ( $opt == 'submit' ) continue;

			foreach ( array( 'datatype', 'default', 'help', 'input', 'input_attributes', 'label', 'no_wrap', 'options' ) as $attrib ) {
				if ( !isset( $this->config[$opt][$attrib] ) )
					$this->config[$opt][$attrib] = '';
			}

			$input = $this->config[$opt]['input'];
			$label = $this->config[$opt]['label'];
			if ( $input == 'none' ) {
				if ( $opt == 'more' ) {
					$i++; $j++;
					echo "<p>$label</p>";
					echo "<div class='widget-group widget-group-$i'>";
				} elseif ( $opt == 'endmore' ) {
					$j--;
					echo '</div>';
				}
				continue;
			}
			if ( $input == 'checkbox' ) {
				$checked = ( $value == 1 ) ? 'checked=checked ' : '';
				$value = 1;
			} else {
				$checked = '';
			};
			if ( $input == 'multiselect' ) {
				// Do nothing since it needs the values as an array
			} elseif ( $this->config[$opt]['datatype'] == 'array' ) {
				if ( !is_array( $value ) )
					$value = '';
				else
					$value = implode( ('textarea' == $input ? "\n" : ', '), $value );
			} elseif ( $this->config[$opt]['datatype'] == 'hash' ) {
				if ( !is_array( $value ) )
					$value = '';
				else {
					$new_value = '';
					foreach ( $value AS $shortcut => $replacement )
						$new_value .= "$shortcut => $replacement\n";
					$value = $new_value;
				}
			}
			echo "<p>";
			$input_id = $this->get_field_id( $opt );
			$input_name = $this->get_field_name( $opt );
			$value = esc_attr( $value );
			if ( $label && ( $input != 'multiselect' ) ) echo "<label for='$input_id'>$label:</label> ";
			if ( $input == 'textarea' ) {
				echo "<textarea name='$input_name' id='$input_id' class='widefat' {$this->config[$opt]['input_attributes']}>" . $value . '</textarea>';
			} elseif ( $input == 'select' ) {
				echo "<select name='$input_name' id='$input_id'>";
				foreach ( (array) $this->config[$opt]['options'] as $sopt ) {
					$selected = $value == $sopt ? " selected='selected'" : '';
					echo "<option value='$sopt'$selected>$sopt</option>";
				}
				echo "</select>";
			} elseif ( $input == 'multiselect' ) {
				echo '<fieldset style="border:1px solid #ccc; padding:2px 8px;">';
				if ( $label ) echo "<legend>$label: </legend>";
				foreach ( (array) $this->config[$opt]['options'] as $sopt ) {
					$selected = in_array( $sopt, $value ) ? " checked='checked'" : '';
					echo "<input type='checkbox' name='$input_name' id='$input_id' value='$sopt'$selected>$sopt</input><br />";
				}
				echo '</fieldset>';
			} elseif ( !empty( $input ) ) { // If no input defined, then not valid input
				if ( $input == 'short_text' ) {
					$tclass = '';
					$tstyle = 'width:25px;';
					$input = 'text';
				} else {
					$tclass = 'widefat';
					$tstyle = '';
				}
				echo "<input name='$input_name' type='$input' id='$input_id' value='$value' class='$tclass' style='$tstyle' $checked {$this->config[$opt]['input_attributes']} />";
			}
			if ( $this->config[$opt]['help'] )
				echo "<br /><span style='color:#888; font-size:x-small;'>({$this->config[$opt]['help']})</span>";
			echo "</p>\n";
		}
		// Close any open divs
		for ( ; $j > 0; $j-- ) { echo '</div>'; }
	}

} // end class c2c_LinkifyAuthorsWidget

add_action( 'widgets_init', create_function('', 'register_widget(\'c2c_LinkifyAuthorsWidget\');') );

endif; // end if !class_exists()
?>