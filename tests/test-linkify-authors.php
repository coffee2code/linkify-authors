<?php

class Linkify_Authors_Test extends WP_UnitTestCase {

	private static $user_ids = array();

	function setUp() {
		parent::setUp();
		$this->user_ids = $this->factory->user->create_many( 5 );
	}


	/*
	 *
	 * HELPER FUNCTIONS
	 *
	 */


	function get_slug( $user_id ) {
		return get_user_by( 'id', $user_id )->user_nicename;
	}

	function expected_output( $count, $lowest_id, $between = ', ', $user_num = 1 ) {
		$str = '';
		$j = $lowest_id;
		for ( $n = 1, $i = $user_num; $n <= $count; $n++, $i++ ) {
			if ( ! empty( $str ) ) {
				$str .= $between;
			}
			$str .= '<a href="http://example.org/?author=' . $j . '" title="Posts by User ' . $i . '">User ' . $i . '</a>';
			$j++;
		}
		return $str;
	}

	function get_results( $args, $direct_call = true, $use_deprecated = false ) {
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


	/*
	 *
	 * TESTS
	 *
	 */


	function test_single_id() {
		$this->assertEquals( $this->expected_output( 1, $this->user_ids[0] ), $this->get_results( array( $this->user_ids[0] ) ) );
		$this->assertEquals( $this->expected_output( 1, $this->user_ids[0] ), $this->get_results( array( $this->user_ids[0], false ) ) );
	}

	function test_array_of_ids() {
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $this->user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $this->user_ids ), false ) );
	}

	function test_single_slug() {
		$user = get_user_by( 'id', $this->user_ids[0] );
		$this->assertEquals( $this->expected_output( 1, $user->ID ), $this->get_results( array( $user->data->user_nicename ) ) );
	}

	function test_array_of_slugs() {
		$user_slugs = array_map( array( $this, 'get_slug' ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_slugs ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_slugs ), false ) );
	}

	function test_slug_is_login() {
		$user_login = 'alpha';
		$user_id = $this->factory->user->create( array( 'user_login' => $user_login, 'user_nicename' => 'bravo' ) );
		$expected = '<a href="http://example.org/?author=' . $user_id . '" title="Posts by ' . $user_login . '">' . $user_login . '</a>';

		$this->assertEquals( $expected, $this->get_results( array( $user_login ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $user_login ), false ) );
	}

	function test_display_name_is_used_for_display() {
		$user_login = 'alpha';
		$display_name = 'Example User';
		$user_id = $this->factory->user->create( array( 'user_login' => $user_login, 'user_nicename' => 'bravo', 'display_name' => $display_name ) );
		$expected = '<a href="http://example.org/?author=' . $user_id . '" title="Posts by ' . $display_name . '">' . $display_name . '</a>';

		$this->assertEquals( $expected, $this->get_results( array( $user_login ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $user_login ), false ) );
	}

	function test_all_empty_authors() {
		$this->assertEmpty( $this->get_results( array( '' ) ) );
		$this->assertEmpty( $this->get_results( array( array() ) ) );
		$this->assertEmpty( $this->get_results( array( array( array(), '' ) ) ) );
	}

	function test_an_empty_author() {
		$user_ids = array_merge( array( '' ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_ids ), false ) );
	}

	function test_all_invalid_authors() {
		$this->assertEmpty( $this->get_results( array( 99999999 ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-an-author' ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-an-author' ), false ) );
	}

	function test_an_invalid_author() {
		$user_ids = array_merge( array( 99999999 ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_ids ) ) );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_ids ), false ) );
	}

	function test_arguments_before_and_after() {
		$expected = '<div>' . $this->expected_output( 5, $this->user_ids[0] ) . '</div>';
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<div>', '</div>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<div>', '</div>' ), false ) );
	}

	function test_argument_between() {
		$expected = '<ul><li>' . $this->expected_output( 5, $this->user_ids[0], '</li><li>' ) . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<ul><li>', '</li></ul>', '</li><li>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '<ul><li>', '</li></ul>', '</li><li>' ), false ) );
	}

	function test_argument_before_last() {
		$before_last = ', and ';
		$expected = $this->expected_output( 4, $this->user_ids[0] ) . $before_last . $this->expected_output( 1, $this->user_ids[4], ', ', 5 );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '', '', ', ', $before_last ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->user_ids, '', '', ', ', $before_last ), false ) );
	}

	function test_argument_none() {
		$missing = 'No authors to list.';
		$expected = '<ul><li>' . $missing . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ) ) );
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ), false ) );
	}

	/**
	 * @expectedDeprecated linkify_authors
	 */
	function test_deprecated_function() {
		$this->assertEquals( $this->expected_output( 1, $this->user_ids[0] ), $this->get_results( array( $this->user_ids[0] ), false, true ) );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $this->user_ids ), false, true ) );
		$user = get_user_by( 'id', $this->user_ids[0] );
		$this->assertEquals( $this->expected_output( 1, $user->ID ), $this->get_results( array( $user->data->user_nicename ), false, true ) );
		$user_slugs = array_map( array( $this, 'get_slug' ), $this->user_ids );
		$this->assertEquals( $this->expected_output( 5, $this->user_ids[0] ), $this->get_results( array( $user_slugs ), false, true ) );
	}

}
