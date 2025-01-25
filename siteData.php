<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, 		//Plugin id
	'siteData', 	//Plugin name
	'2.0', 			//Plugin version
	'Multicolor', 	//Plugin author
	'https://ko-fi.com/multicolorplugins/', //author website
	'Add custom easy to update fields in your site templates, like Phone Number, Email Address, Business Hours, etc.', //Plugin description
	'pages', 		//page type - on which admin tab to display
	'siteDataBackend' //main function (administration)
);

# activate filter 

# add a link in the admin tab 'theme'
add_action('pages-sidebar', 'createSideMenu', array($thisfile, 'Site Data <svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="24" height="24" viewBox="0 0 24 24"><rect width="24" height="24" fill="none"/><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M21.5 16.052V7.948a4.14 4.14 0 0 0-1.236-2.945a4.25 4.25 0 0 0-2.985-1.22H6.72a4.25 4.25 0 0 0-2.985 1.22A4.14 4.14 0 0 0 2.5 7.948v8.104c0 1.105.445 2.164 1.236 2.945a4.25 4.25 0 0 0 2.985 1.22H17.28c1.12 0 2.193-.44 2.985-1.22a4.14 4.14 0 0 0 1.236-2.945"/><path d="M8.552 12.14a2.054 2.054 0 1 0 0-4.108a2.054 2.054 0 0 0 0 4.108m3.081 3.828c0-.812-.324-1.59-.902-2.165a3.09 3.09 0 0 0-4.358 0a3.05 3.05 0 0 0-.902 2.165m9.097-7.049h3.594M14.568 12h1.54m-1.54 3.081h3.594"/></g></svg>'));
add_action('theme-header', 'siteDataShortcode');

# functions
function siteDataBackend(){
	include(GSPLUGINPATH . 'siteData/backend.php');
}

function siteDataReturn($matches){
	$match = $matches[1];
	$file = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');
	$jsfile = json_decode($file, true);

	foreach ($jsfile as $item) {
		if ($item['id'] == $match) {
			return str_replace("\u0027", "'",$item['data']);;
		};
	};
};

function siteData($matches){
	$match = $matches;
	$file = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');
	$jsfile = json_decode($file, true);

	foreach ($jsfile as $item) {
		if ($item['id'] == $match) {
			echo str_replace("\u0027", "'",$item['data']);
		};
	};
};

function siteDataShortcode(){
	global $content;
	$newcontent = preg_replace_callback(
		'/\\[% siteData=(.*) %\\]/i',
		"siteDataReturn",
		$content
	);
	$content = $newcontent;
}
?>
