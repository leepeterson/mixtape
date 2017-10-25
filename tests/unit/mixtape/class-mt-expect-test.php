<?php

class MT_ExpectTest extends MT_Testing_TestCase {

    function test_exists() {
        $this->assertClassExists( 'MT_Expect' );
    }

    /**
     * @expectedException MT_Exception
     */
    function test_that_throws_if_false() {
        $this->mixtape = MT_Bootstrap::create()->load();
        MT_Expect::that( false, 'fails' );
    }

    /**
     * @expectedException MT_Exception
     */
    function test_is_a_throws_if_not_class() {
        $this->mixtape = MT_Bootstrap::create()->load();
        MT_Expect::is_a( new stdClass(), 'MT_Environment');
    }

    /**
     * @expectedException MT_Exception
     */
    function test_implements_interface_throws_if_interface_not_implemented() {
        $this->mixtape = MT_Bootstrap::create()->load();
        MT_Expect::implements_interface( new stdClass(), 'MT_Interfaces_Model' );
    }
}