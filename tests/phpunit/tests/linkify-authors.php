<?php

defined( 'ABSPATH' ) or die();

class Linkify_Authors_Test extends WP_UnitTestCase {

	private $user_ids = array();

	public function setUp() {
		parent::setUp();
		$this->user_ids = $this->factory->user->create_many( 5 );
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function get_slug( $user_id ) {
		return get_user_by( 'id', $user_id )->user_nicename;
	}

	/**
	 * Returns the expected output.
	 *
	 * @param int    $count      The number of authors to list.
	 * @param int    $user_index Optional. The index into the $user_ids array to start at. Default 0.
	 * @param string $between    Optional. The string to appear between authors. Default ', '.
	 * @param int    $user_num   Optional. The user number. Default 1.
	 * @return string
	 */
	protected function expected_output( $count, $user_index = 0, $between = ', ', $user_num = 1 ) {
		$str = '';
		for ( $n = 1; $n <= $count; $n++, $user_index++ ) {
			if ( ! empty( $str ) ) {
				$str .= $between;
			}
			$user = get_userdata( $this->user_ids[ $user_index ] );
			$str .= '<a href="http://example.org/?author=' . $user->ID . '" title="Posts by ' . $user->display_name . '">' . $user->display_name . '</a>';
		}

		return $str;
	}

	protected function get_results( $args, $direct_call = true, $use_deprecated = false ) {
		ob_start();

		$function = $use_deprecated ? 'linkify_authors' : 'c2c_linkify_authors';

		if ( $direct_call ) {
			call_user_func_array( $function, $args );
		} else {
			do_action_ref_array( $function, $args );
		}

		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}


	//
	//
	// TESTS
	//
	//


	public function test_widget_class_name() {
		$this->assertTrue( class_exists( 'c2c_LinkifyAuthorsWidget' ) );
	}

	public function test_widget_version() {
		$this->assertEquals( '004', c2c_LinkifyAuthorsWidget::version() );
	}

	public function test_widget_hooks_widgets_init() {
		$this->assertEquals( 10, has_filter( 'widgets_init', array( 'c2c_LinkifyAuthorsWidget', 'register_widget' ) ) );
	}

	public function test_widget_made_available() {
		$this->assertContains( 'c2c_LinkifyAuthorsWidget', array_keys( $GLOBALS['wp_widget_factory']->widgets ) );
	}

	public function test_single_id() {
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->user_ids[0] ) ) );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->user_ids[0], false ) ) );
	}

	public function test_array_of_ids() {
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->user_ids ), false ) );
	}

	public function test_single_slug() {
		$user = get_user_by( 'id', $this->user_ids[0] );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $user->data->user_nicename ) ) );
	}

	public function test_array_of_slugs() {
		$user_slugs = array_map( array( $this, 'get_slug' ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_slugs ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_slugs ), false ) );
	}

	public function test_slug_is_login() {
		$user_login = 'alpha';
		$user_id = $this->factory->user->create( array( 'user_login' => $user_login, 'user_nicename' => 'bravo' ) );
		$expected = '<a href="http://example.org/?author=' . $user_id . '" title="Posts by ' . $user_login . '">' . $user_login . '</a>';

		$this->assertEquals( $expected, $this->get_results( array( $user_login ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $user_login ), false ) );
	}

	public function test_display_name_is_used_for_display() {
		$user_login = 'alpha';
		$display_name = 'Example User';
		$user_id = $this->factory->user->create( array( 'user_login' => $user_login, 'user_nicename' => 'bravo', 'display_name' => $display_name ) );
		$expected = '<a href="http://example.org/?author=' . $user_id . '" title="Posts by ' . $display_name . '">' . $display_name . '</a>';

		$this->assertEquals( $expected, $this->get_results( array( $user_login ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $user_login ), false ) );
	}

	public function test_all_empty_authors() {
		$this->assertEmpty( $this->get_results( array( '' ) ) );
		$this->assertEmpty( $this->get_results( array( array() ) ) );
		$this->assertEmpty( $this->get_results( array( array( array(), '' ) ) ) );
	}

	public function test_an_empty_author() {
		$user_ids = array_merge( array( '' ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_ids ), false ) );
	}

	public function test_all_invalid_authors() {
		$this->assertEmpty( $this->get_results( array( 99999999 ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-an-author' ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-an-author' ), false ) );
	}

	public function test_an_invalid_author() {
		$user_ids = array_merge( array( 99999999 ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $user_ids ), false ) );
	}

	public function test_arguments_before_and_after() {
		$expected = '<div>' . $this->expected_output( 5 ) . '</div>';
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<div>', '</div>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<div>', '</div>' ), false ) );
	}

	public function test_argument_between() {
		$expected = '<ul><li>' . $this->expected_output( 5, 0, '</li><li>' ) . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<ul><li>', '</li></ul>', '</li><li>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<ul><li>', '</li></ul>', '</li><li>' ), false ) );
	}

	public function test_argument_before_last() {
		$before_last = ', and ';
		$expected = $this->expected_output( 4 ) . $before_last . $this->expected_output( 1, 4, ', ', 5 );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '', '', ', ', $before_last ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '', '', ', ', $before_last ), false ) );
	}

	public function test_argument_none() {
		$missing = 'No authors to list.';
		$expected = '<ul><li>' . $missing . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ) ) );
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ), false ) );
	}

	/*
	 * __c2c_linkify_authors_get_author_link()
	 */

	public function test___c2c_linkify_authors_get_author_link() {
		$title = get_the_author_meta( 'display_name', $this->user_ids[0] );
		$expected = sprintf(
			'<a href="http://example.org/?author=%d" title="Posts by %s">%s</a>',
			esc_attr( $this->user_ids[0] ),
			esc_attr( $title ),
			esc_html( $title )
		);

		$this->assertEquals(
			$expected,
			__c2c_linkify_authors_get_author_link( $this->user_ids[0] )
		);
	}

	public function test___c2c_linkify_authors_get_author_lin_with_invalid_id() {
		$this->assertEmpty( __c2c_linkify_authors_get_author_link( -1 ) );
	}

	public function test___c2c_linkify_authors_get_author_link_for_user_with_no_display_name() {
		add_filter( 'get_the_author_display_name', '__return_empty_string' );

		$this->assertEquals( '', __c2c_linkify_authors_get_author_link( $this->user_ids[0] ) );
	}

}
