<?php

defined( 'ABSPATH' ) or die();

class Linkify_Widget_Test extends WP_UnitTestCase {

	private $user_ids = array();

	public function setUp(): void {
		parent::setUp();
		$this->user_ids = $this->factory->user->create_many( 6 );
		for ( $i = 0; $i < 4; $i++ ) {
			$this->factory->post->create( array( 'post_author' => $this->user_ids[ $i ] ) );
		}
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//

	protected function get_results( $widget, $args, $instance, $full_widget_content = true ) {
		ob_start();

		$function = $full_widget_content ? 'widget' : 'widget_content';

		call_user_func_array( array( $widget, $function ), array( $args, $instance ) );

		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	private function widget_init( $config = array() ) {
		c2c_LinkifyAuthorsWidget::register_widget();
		$widget = new c2c_LinkifyAuthorsWidget();

		$default_config = array();
		foreach ( $widget->get_config() as $key => $val ) {
			$default_config[ $key ] = $val['default'];
		}
		$config = array_merge( $default_config, $config );

		$settings = array(
			'before_title'  => '<h3>',
			'before_widget' => '<div class="my-widget">',
			'after_title'   => '</h3>',
			'after_widget'  => '</div>'
		);

		return array( $widget, $config, $settings );
	}


	//
	//
	// TESTS
	//
	//


	public function test_widget_class_exists() {
		$this->assertTrue( class_exists( 'c2c_LinkifyAuthorsWidget' ) );
	}

	public function test_widget_version() {
		$this->assertEquals( '006', c2c_LinkifyAuthorsWidget::version() );
	}

	public function test_widget_framework_class_name() {
		$this->assertTrue( class_exists( 'c2c_LinkifyWidget_006' ) );
	}

	public function test_widget_framework_version() {
		$this->assertEquals( '006', c2c_LinkifyWidget_006::version() );
	}

	public function test_widget_hooks_widgets_init() {
		$this->assertEquals( 10, has_filter( 'widgets_init', array( 'c2c_LinkifyAuthorsWidget', 'register_widget' ) ) );
	}

	public function test_widget_made_available() {
		$this->assertArrayHasKey( 'c2c_LinkifyAuthorsWidget', $GLOBALS['wp_widget_factory']->widgets );
	}

	public function test_get_config() {
		list( $widget, $config, $settings ) = $this->widget_init( array( 'authors' => $this->user_ids[0], 'before' => 'Author: ', 'after' => '.' )  );

		$this->assertEqualsCanonicalizing(
			[ 'after', 'authors', 'before', 'before_last', 'between', 'none', 'title', ],
			array_keys( $widget->get_config() )
		);

		$this->assertEqualsCanonicalizing(
			[ 'default', 'help', 'input', 'label' ],
			array_keys( $widget->get_config()['authors'] )
		);
	}

	public function test_widget_body() {
		list( $widget, $config, $settings ) = $this->widget_init( array( 'authors' => $this->user_ids[0], 'before' => 'Author: ', 'after' => '.' )  );

		$this->assertEquals(
			sprintf( '<div class="my-widget"><h3>Authors</h3>Author: %s.</div>', __c2c_linkify_authors_get_author_link( $this->user_ids[0] ) ),
			$this->get_results( $widget, $settings, $config )
		);
	}

	public function test_widget_body_with_multiple_authors() {
		list( $widget, $config, $settings ) = $this->widget_init( array( 'authors' => $this->user_ids[0] . ',' . $this->user_ids[1], 'before' => '<ul><li>', 'after' => '</li></ul>', 'between' => '</li><li>' )  );

		$this->assertEquals(
			sprintf(
				'<div class="my-widget"><h3>Authors</h3><ul><li>%s</li><li>%s</li></ul></div>',
				__c2c_linkify_authors_get_author_link( $this->user_ids[0] ),
				__c2c_linkify_authors_get_author_link( $this->user_ids[1] )
			),
			$this->get_results( $widget, $settings, $config )
		);
	}

	public function test_widget_body_with_invalid_authors() {
		list( $widget, $config, $settings ) = $this->widget_init( array( 'authors' => 'bogus', 'before' => '<ul><li>', 'after' => '</li></ul>', 'between' => '</li><li>' )  );

		$this->assertEquals(
			'<div class="my-widget"><h3>Authors</h3><ul><li>No authors specified to be displayed</li></ul></div>',
			$this->get_results( $widget, $settings, $config )
		);
	}

	public function test_widget_body_with_no_specified_authors() {
		list( $widget, $config, $settings ) = $this->widget_init( array( 'authors' => '', 'before' => '<ul><li>', 'after' => '</li></ul>', 'between' => '</li><li>' )  );

		$this->assertEquals(
			'<div class="my-widget"><h3>Authors</h3><ul><li>No authors specified to be displayed</li></ul></div>',
			$this->get_results( $widget, $settings, $config )
		);
	}

	public function test_output_omits_unsafe_markup() {
		list( $widget, $config, $settings ) = $this->widget_init( array(
			'authors' => array_slice( $this->user_ids, 0, 2 ),
			'before'  => '<ul><li><script>alert("boom!");</script>',
			'after'   => '<script>alert("boom!");</script></li></ul>',
			'between' => '</li><li><script>alert("boom!");<strong><span class="test">Hi</span></strong></script></li><li>'
		) );

		$settings = array(
			'before_title'  => '<h3><script>alert("boom!");</script>',
			'before_widget' => '<div class="my-widget"><script>alert("boom!");</script>',
			'after_title'   => '<script>alert("boom!");</script></h3>',
			'after_widget'  => '<script>alert("boom!");</script></div>'
		);

		$this->assertEquals(
			sprintf(
				'<div class="my-widget">alert("boom!");<h3>alert("boom!");Authorsalert("boom!");</h3><ul><li>alert("boom!");%s</li><li>alert("boom!");<strong><span class="test">Hi</span></strong></li><li>%salert("boom!");</li></ul>alert("boom!");</div>',
				__c2c_linkify_authors_get_author_link( $this->user_ids[0] ),
				__c2c_linkify_authors_get_author_link( $this->user_ids[1] )
			),
			$this->get_results( $widget, $settings, $config )
		);
	}

}
