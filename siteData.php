<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'siteData 🤖', 	//Plugin name
	'1.0', 		//Plugin version
	'Multicolor',  //Plugin author
	'https://ko-fi.com/multicolorplugins/', //author website
	'Add custom easy to update fields in your site templates, like Phone Number, Email Address, Business Hours, etc.', //Plugin description
	'plugins', //page type - on which admin tab to display
	'siteDataBackend'  //main function (administration)
);

# activate filter 

# add a link in the admin tab 'theme'
add_action('pages-sidebar', 'createSideMenu', array($thisfile, 'SiteData 🤖'));
add_action('theme-header', 'siteDataShortcode');

# functions
function siteDataBackend()
{
	include(GSPLUGINPATH . 'siteData/backend.php');
}

function siteDataReturn($matches)
{
	$match = $matches[1];
	$file = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');
	$jsfile = json_decode($file, true);

	foreach ($jsfile as $item) {
		if ($item['id'] == $match) {
			return str_replace("\u0027", "'",$item['data']);;
		};
	};
};

function siteData($matches)
{
	$match = $matches;
	$file = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');
	$jsfile = json_decode($file, true);

	foreach ($jsfile as $item) {
		if ($item['id'] == $match) {
			echo str_replace("\u0027", "'",$item['data']);
		};
	};
};

function siteDataShortcode()
{
	global $content;
	$newcontent = preg_replace_callback(
		'/\\[% siteData=(.*) %\\]/i',
		"siteDataReturn",
		$content
	);
	$content = $newcontent;
}
?>
