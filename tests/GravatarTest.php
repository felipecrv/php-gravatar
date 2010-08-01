<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../src/Gravatar.php';

class GravatarTest extends PHPUnit_Framework_TestCase
{
    protected $gravatar;
    protected $email = 'lars@legestue.net';

    function setUp()
    {
        $this->gravatar = new Gravatar($this->email);
    }

    function testGetEmail()
    {
        $this->assertEquals($this->email, $this->gravatar->get_email());
    }

    function testAllFunctions()
    {
        $grav2 = new Gravatar(
            array(
                'default' => 'identicon',
                'size'    => 128,
                'rating'  => 'X',
                'border'  => 'F00',
                'file_extension' => 'png',
                'extra'   => 'class="test"'
            )
        );
        $grav2->set_email($this->email);
        $this->assertEquals('X', $grav2->rating);
        $this->assertEquals('F00', $grav2->border);
        $this->assertTrue($grav2->avatar_exists());
    }

    function testGrvatarExists()
    {
        $gravatar = new Gravatar($this->email);
        $this->assertTrue($gravatar->avatar_exists());

        $gravatar = new Gravatar('doesnotexits@example.dk');
        $this->assertFalse($gravatar->avatar_exists());
    }

    function testGetSrc()
    {
        $this->assertEquals('http://gravatar.com/avatar/98c28fcbe1e816f8e64ff24302298e81?s=80', $this->gravatar->get_src());
    }

    function testToHtml()
    {
        $this->assertEquals('<img src="http://gravatar.com/avatar/98c28fcbe1e816f8e64ff24302298e81?s=80" width="80" height="80" />', $this->gravatar->to_HTML());
    }
}
