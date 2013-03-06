<?php
	define ('ALLOW_EXTERNAL', TRUE);
	define ('ALLOW_ALL_EXTERNAL_SITES', true);
	define ('FILE_CACHE_DIRECTORY', '../../user/cache');
	
	//copy form timthumb.php file. Add domains you wish to enable 
	$ALLOWED_SITES = array (
	    'apollo13.eu', /* for demo data */
        'flickr.com',
        'picasa.com',
        'img.youtube.com',
        'imgur.com',
        'imageshack.us',
        'photobucket.com',
        'staticflickr.com',
        'tinypic.com',
        'upload.wikimedia.org',
    );
?>