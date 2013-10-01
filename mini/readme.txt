MiniCMS
Author: Yusuf Bhabhrawala

MiniCMS is the smallest Content Management System based on boiler plate implementation of the template architecture of Joomla CMS. 
It requires some knowledge of PHP and HTML to set it up, but then so does other CMS'es. 
The main idea was to remove the bloat of other CMS that is not necessary for smaller sites and make this work without a database dependency.

---------------------------
Main Concepts
---------------------------
Content is of two main type:
1. Content
2. Modules

Content is the primary focus of any page.

Modules are tied to regions. A <region>.config file determines which modules are loaded for the given URL.

A templates requires two main php function calls:
1. $cms->content();
2. $cms->region("<region name>");

---------------------
Folder/File structure of content/modules
---------------------
The general folder structure of the content is:
/contents/
/contents/<Path>.php

Modules:
/contents/modules/
/contents/modules/<region>.config
/contents/modules/<module>.php

---------------------
Locating content
---------------------
Content can be located based on the folder/file part of the URL.
eg. http://localhost/events/current.php => events/current 
Here:
events/current is called path
events is path - 1

Loop:
1. /contents/<path>.php
2. /contents/<path - 1>/index.php
path = path - 1

(PS: It's not as complicated as it looks!)

---------------------
Region Config
---------------------
The <region>.config files are a json files with the format:
{
<module>:[<path pattern 1>,<path pattern 2>]
}

If the path matches any of the patterns, the module is included in that region.

---------------------
Modules
---------------------
Modules are the mini content. They are located in content/modules/<module>.php file.


---------------------
$cms goodies
---------------------
$cms->title : When this variable is set, the title is in the format: $cms->title - site_name (as in config)
$cms->meta[<keyword>] = "<content>" : Add's the meta tag for the corresponding meta keyword/content.
$cms->css[] : Set this to any css file and it will be included in the header using link tag.
$cms->js[] : Set this to any javascript file and it will be included in the header using script tag.

$cms->style[] : Set this some multiline css style and it will be included in the header under style tag.
$cms->script[] : Add any arbitrary script using this variable and it will including under script tag as inline javascript.

$cms->args : Contains the array of the URL. eg. events/current.php will have ['events','current']
$cms->path : Contains cleaned up path of the URL. eg. events/current.php?date=1 will have "events/current"

---------------------
Templates
---------------------
Following is a boilerplace template:

include('mini/cms.php'); 

// get the content before as the code may set the title etc.
$content = $cms->content();
$left = $cms->region("left");

<html>
	<head>
		<?php $cms->head(); ?>
	</head>
	<body>
		<div id="leftColumn">
			<?php echo $left; ?>
		</div>
		<div id="content">
			<?php echo $content; ?>
		</div>
	</body>
</html>
