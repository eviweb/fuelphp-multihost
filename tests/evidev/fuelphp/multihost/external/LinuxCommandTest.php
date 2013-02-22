<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * The MIT License
 *
 * Copyright 2013 Eric VILLARD <dev@eviweb.fr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @package     multihost
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright	(c) 2013 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */

namespace evidev\fuelphp\multihost\test;
use \evidev\fuelphp\multihost\external\LinuxCommand;

/**
 * LinuxCommand unit test
 * 
 * @package     multihost
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright	(c) 2013 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */
class LinuxCommandTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * external command to test
	 * 
	 * @var string
	 */
	protected $command = 'uname';
	
	/**
	 * checks test environment
	 */
	protected function setUp()
	{
		if (strtolower(PHP_OS) != 'linux') {
			$this->markTestSkipped('Not a Linux Operating System');
		}
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::create
	 */
	public function testCreate()
	{
		$expected = '\\evidev\\fuelphp\\multihost\\external\\LinuxCommand';
		$lcmd = LinuxCommand::create($this->command);
		$this->assertInstanceOf($expected, $lcmd);
		$this->assertAttributeEquals($this->command, 'name', $lcmd);
	}

	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::exists
	 */
	public function testExists()
	{
		$this->assertTrue(LinuxCommand::create($this->command)->exists());
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::run
	 */
	public function testRunWithNoArgs()
	{
		$this->assertEquals(
			'linux',
			strtolower(LinuxCommand::create($this->command)->run())
		);
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::run
	 */
	public function testRunWithStringArgs()
	{
		$hostname = trim(strtolower(file_get_contents("/etc/hostname")));
		$this->assertEquals(
			$hostname.' gnu/linux',
			strtolower(LinuxCommand::create($this->command)->run('-n -o'))
		);
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::run
	 */
	public function testRunWithArrayArgs()
	{
		$hostname = trim(strtolower(file_get_contents("/etc/hostname")));
		$this->assertEquals(
			$hostname.' gnu/linux',
			strtolower(LinuxCommand::create($this->command)->run(array('-n', '-o')))
		);
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::run
	 * @expectedException \evidev\fuelphp\multihost\external\InvalidCommandException
	 */
	public function testRunThrowsException()
	{
		LinuxCommand::create("unknown_command")->run();
	}
	
	/**
	 * @covers \evidev\fuelphp\multihost\external\LinuxCommand::getPath
	 */
	public function testGetPath()
	{
		$this->assertEquals(
			'/bin/'.$this->command,
			LinuxCommand::create($this->command)->getPath()
		);
		$this->assertEquals(
			"",
			LinuxCommand::create("unknown_command")->getPath()
		);
	}
}