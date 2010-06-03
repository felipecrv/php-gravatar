<?php

/**
 * Gravatar class
 *
 * {@link http://site.gravatar.com/site/implement}
 *
 * Very basic usage:
 * <code>
 *  $gravatar = new Gravatar("user@gmail.com");
 *  echo $gravatar; // <img src="http://gravatar.com/...
 * </code>
 *
 * @package Gravatar
 * @version 1.0
 * @copyright 2008-2010 Felipe Oliveira Caravalho
 * @author Felipe Oliveira Carvalho <felipekde@gmail.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */
class Gravatar
{
	/**
	 * Gravatar's base url 
	 */
	const GRAVATAR_URL = 'http://gravatar.com/avatar/';

	/**
	 * Available ratings
	 *
	 * @var string
	 * @access private
	 */
	private $GRAVATAR_RATING = array('G', 'PG', 'R', 'X');

	/**
	 * Gravatar URL properties:
	 * - gravatar_id
	 * - default, d (identicon, monsterid, wavatar, <url>)
	 * - size, s
	 * - rating, r
	 * - border, b
	 *
	 * @link http://en.gravatar.com/site/implement/url More information
	 *
	 * @var array
	 * @access protected
	 */
	protected $properties = array(
		'default'	=> NULL,
		'size'		=> 80,
		'rating'	=> NULL,
		'border'	=> NULL,
	);

	/**
	 * Email
	 * 
	 * @var string
	 * @access protected
	 */
	protected $email = '';


	/**
	 * Optional file extension (png, gif, jpg...)
	 * 
	 * @var string
	 * @access protected
	 */
	protected $file_extension = '';

	/**
	 * Extra attributes to the IMG tag like ALT, CLASS, STYLE...
	 * 
	 * @var string
	 * @access protected
	 */
	protected $extra = '';

	/**
	 * Gravatar constructor
	 * 
	 * @param array|string $props may be an email or a configuration array
	 * @access public
	 * @return void
	 */
	function Gravatar($props = array())
	{
		if (is_array($props))
			return $this->initialize($props);
		// In case it's an email
		$this->set_email($props);
	}

	/**
	 * This method allows CodeIgniter integration.
	 * The options you can set are the following:
	 *  - default
	 *  - size
	 *  - rating
	 *  - email (you won't use this in a configuration file)
	 *  - file_extension
	 *  - extra
	 * 
	 * @param array $config 
	 * @access public
	 * @return void
	 */
	function initialize($config = array())
	{
		foreach($config as $key => $val) {
			$method = 'set_'.$key;
			$this->$method($val);
		}
		
		$this->properties['gravatar_id'] = NULL;
	}

	/**
	 * Set email
	 * 
	 * @param string $email 
	 * @access public
	 * @return boolean indicates if the email is valid
	 */
	function set_email($email)
	{
		if ($this->is_valid_email($email)) {
			$this->email = $email;
			$this->properties['gravatar_id'] = md5(strtolower($this->email));
			return true;
		}
		return false;
	}

	/**
	 * Set the value of the 'default' parameter
	 * 
	 * @param string $default 
	 * @access public
	 * @return void
	 */
	function set_default($default)
	{
		$this->properties['default'] = $default;
	}

	/**
	 * Set rating 
	 * 
	 * @param string $rating 
	 * @access public
	 * @return boolean indicates if the rating is valid
	 */
	function set_rating($rating)
	{
		if (in_array($rating, $this->GRAVATAR_RATING)) {
			$this->properties['rating'] = $rating;
			return true;
		}
		return false;
	}

	/**
	 * Set the avatar size
	 * 
	 * @param integer $size 
	 * @access public
	 * @return void
	 */
	function set_size($size)
	{
		$size = (int) $size;
		if ($size <= 0)
			$size = NULL;  // Use the default size
		$this->properties['size'] = $size;
	}
	
	/**
	 * Set extra html data that will be inserted inside the <img> tag
	 * 
	 * @param string $extra 
	 * @access public
	 * @return void
	 */
	function set_extra($extra)
	{
		if ($this->extra == '')
			$this->extra = $extra;
		else
			$this->extra .= ' '.$extra;
	}

	/**
	 * Set the optional file extension ('jpg', 'png', 'gif'...)
	 * 
	 * @param string $ext 
	 * @access public
	 * @return void
	 */
	function set_file_extension($ext)
	{
		$this->file_extension = $ext;
	}

	/**
	 * Set the optional avatar 1px border color (e.g. FF0000, F00)
	 *
	 * @param string border
	 * @return void
	 */
	function set_border($border)
	{
		$this->properties['border'] = $border;
	}

	/**
	 * Tests whether a email is valid 
	 * 
	 * @param string $email 
	 * @access public
	 * @return void
	 */
	function is_valid_email($email)
	{
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$/i", $email);
	}

	/**
	 * Tests whether a gravatar exists
	 * {@link http://codex.wordpress.org/Using_Gravatars}
	 *
	 * @return boolean
	 */
	function avatar_exists()
	{
		// Craft a potential url and test its headers
		$hash = $this->properties['gravatar_id'];
		if (is_null($hash))
			return false;
		$uri = self::GRAVATAR_URL . $hash . '?d=404';
		$headers = @get_headers($uri);
		if (!preg_match('|200|', $headers[0]))
			return false;
		return true;
	}

	/**
	 * Returns the email
	 *
	 * @return string
	 */
	function get_email()
	{
		return $this->email;
	}

	/**
	 * __get 
	 * 
	 * @param mixed $var 
	 * @access protected
	 * @return mixed
	 */
	function __get($var)
	{
		return $this->properties[$var];
	}

	/**
	 * __isset 
	 * 
	 * @param string $var 
	 * @access protected
	 * @return boolean
	 */
	function __isset($var)
	{
		return isset($this->properties[$var]);
	}

	/**
	 * __unset 
	 * 
	 * @param string $var 
	 * @access protected
	 * @return boolean
	 */
	function __unset($var)
	{
		return @$this->properties[$var] == NULL;
	}


	/**
	 * Returns the url of the avatar
	 * 
	 * @access public
	 * @return string
	 */
	function get_src()
	{
		$gravatar_id = $this->properties['gravatar_id'];
		$ext = (empty($this->file_extension)) ? '' : '.'.$this->file_extension;
		$url = self::GRAVATAR_URL.$gravatar_id.$ext.'?';
		$first = true;
		foreach($this->properties as $key => $value) {
			if (!is_null($value) && $key != 'gravatar_id') {
				if (!$first)
					$url .= '&';
				$url .= $key[0].'='.urlencode($value);
				$first = false;
			}
		}
		return $url;	
	}

	/**
	 * Return the HTML <img> that represents the avatar 
	 * 
	 * @access public
	 * @return string
	 */
	function to_HTML()
	{
		return  '<img src="'.$this->get_src().'"'
				.(!isset($this->size) ? '' : " width=\"{$this->size}\"	height=\"{$this->size}\"")
				."{$this->extra} />";	
	}

	/**
	 * __toString 
	 * 
	 * @access protected
	 * @return string
	 */
	function __toString()
	{
		return $this->to_HTML();
	}
} 

