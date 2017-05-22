<?php

class MT_Testing_Model_TestCase extends MT_Testing_TestCase {
	/**
	 * @var MT_Environment
	 */
	protected $environment;
	/**
	 * @var MT_Bootstrap
	 */
	protected $mixtape;

	function setUp() {
		parent::setUp();
		$this->mixtape = MT_Bootstrap::create()->load();
		$this->environment = $this->mixtape->environment();
	}
}
