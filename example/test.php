<?php
error_reporting(E_ALL | E_NOTICE);
require('../src/Gravatar.php');

// Get an email address from the test.php?email= URL parameter
$email = $_GET['email'];
$grav1 = new Gravatar($email);

?>
<html>
<head>
<title>Gravatar for <?php echo $grav1->get_email(); ?></title>
</head>
<body>

<h1>Gravatar for <?php echo $grav1->get_email(); ?></h1>

<h2>Basic usage</h2>

<?php
echo $grav1;
?>

<h2>Testing all the options</h2>

<?php
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
$grav2->set_email($email);

// Testing __get() and isset
assert($grav2->rating == 'X');
assert(isset($grav2->border));

if ($grav2->avatar_exists()) {
	echo 'Image link is <a href="' . $grav2->get_src() . '">' . $grav2->get_src() . '</a><br/>';
	echo $grav2;
} else {
	echo 'No Gravatar exists for this email address<br/>';
	echo $grav2; // Showing the identicon
}
?>
</body>
</html>
