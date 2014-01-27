<?php 
// Include the Epub PHP class, via https://github.com/Grandt/PHPePub
// Note the namespace
namespace srss;

// error reporting
error_reporting(E_ALL | E_STRICT);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

// misc vars
$plugin_root=$_SERVER['DOCUMENT_ROOT'].'/plugins/SuperRss';
require_once "$plugin_root/models/EPub/EPub.php";
$loggerClass="$plugin_root/models/EPub/Logger.php";
$cover_img = "$plugin_root/views/shared/items/assets/cover-image_blank.jpg";
$site_url = "http://omeka-upgrade.clevelandhistorical.co";
$date=time();

// set up logging
date_default_timezone_set('America/New_York');
require_once $loggerClass;
$log = new Logger("My Log", TRUE);

// Wrapper
// ePub uses XHTML 1.1, preferably strict
$start =
"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
. "<head>"
. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
. "<title>Test Book</title>\n"
. "</head>\n"
. "<body>\n";
$end = "</body>\n</html>";

// Create the book object
$book= new EPub();
$log->logLine("instantiate epub class");

// Title
$book->setTitle("Test book");
$log->logLine("set title");

// Identifier
$book->setIdentifier($site_url, EPub::IDENTIFIER_URI); 
$log->logLine("set identifier");

// Language
$book->setLanguage("en"); 
$log->logLine("set language");

// Description
$book->setDescription("Blah blah blah");
$log->logLine("set description");

// Author
$book->setAuthor("Erin J. Bell", "Bell, Erin J.");
$log->logLine("set author and author sort key");

// Publisher
$book->setPublisher("Curatescape", "http://curatescape.org/"); 
$log->logLine("set publisher");

// Date
$book->setDate($date); 
$log->logLine("set date");

// Rights statement
$book->setRights("Creative Commons By-NC-SA"); 
$log->logLine("set rights");

// Source
$book->setSourceURL($site_url);
$log->logLine("set source");

// Keyword/subject metadata
$book->addDublinCoreMetadata(DublinCore::CONTRIBUTOR, "Foo");
	$log->logLine("set contributor");
$book->setSubject("Lorem");
$book->setSubject("Ipsum");
$book->setSubject("Test");
	$log->logLine("set subjects");



// Custom application metadata
$book->addCustomMetadata("calibre:series", "My Series");
$book->addCustomMetadata("calibre:series_index", "1");
$log->logLine("set calibre metadata");


// CSS
$cssData = "body{}";

$book->addCSSFile("styles.css", "css1", $cssData);
$log->logLine("add css");

// Cover image
$book->setCoverImage("Cover.jpg", file_get_contents($cover_img), "image/jpeg");
$log->logLine("set cover image");

// Title page
$titlePage = $start . "<h1>Title Page Title</h1>\n<h2>By Title Page Author</h2>\n" . $end;
$book->addChapter("Title Page", "TitlePage.html", $titlePage);
$log->logLine("add title page");

// TOC
//$book->buildTOC(NULL, "toc", "Table of Contents", TRUE, TRUE);
//$log->logLine("add TOC");


// Chapter 1
$chapter1 = $start . "<h1>Chapter 1</h1>\n"
    . "<h2>Lorem ipsum</h2>\n"
    . "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec magna lorem, mattis sit amet porta vitae, consectetur ut eros. Nullam id mattis lacus. In eget neque magna, congue imperdiet nulla. Aenean erat lacus, imperdiet a adipiscing non, dignissim eget felis. Nulla facilisi. Vivamus sit amet lorem eget mauris dictum pharetra. In mauris nulla, placerat a accumsan ac, mollis sit amet ligula. Donec eget facilisis dui. Cras elit quam, imperdiet at malesuada vitae, luctus id orci. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque eu libero in leo ultrices tristique. Etiam quis ornare massa. Donec in velit leo. Sed eu ante tortor.</p>\n"
    . "<p><img src=\"http://www.grandt.com/ePub/AnotherHappilyMarriedCouple.jpg\" alt=\"Test Image retrieved off the internet: Another happily married couple\" />Nullam at tempus enim. Nunc et augue non lectus consequat rhoncus ac a odio. Morbi et tellus eget nisi volutpat tincidunt. Curabitur tristique neque tincidunt purus blandit bibendum. Maecenas eleifend sem quis magna semper id pulvinar nisi porttitor. In in lectus accumsan eros tristique pharetra sit amet ac nulla. Nam vitae felis et orci congue porta nec non ipsum. Donec pretium blandit accumsan. In aliquam lacinia nisi, ut venenatis mauris condimentum ut. Morbi rutrum orci et nisl accumsan euismod. Etiam viverra luctus sem pellentesque suscipit. Aliquam ultricies egestas risus at eleifend. Ut lacinia, tortor non varius malesuada, massa diam aliquet augue, vitae tempor metus tellus eget diam. Nulla vel augue eu elit adipiscing egestas. Duis et nulla est, ac congue arcu. Phasellus semper, ipsum et blandit rutrum, erat ante semper quam, at iaculis quam tellus sed neque.</p>\n"
    . $end;
	$book->addChapter("Chapter 1: Lorem ipsum", "Chapter001.html", $chapter1, true, EPub::EXTERNAL_REF_ADD);
	$log->logLine("add ch. 1");

// Chapter 2
$chapter2 = $start . "<h1>Chapter 2</h1>\n"
    . "<h2>Lorem ipsum</h2>\n"
    . "<h3>Chapter Author</h3>\n"
    . "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec magna lorem, mattis sit amet porta vitae, consectetur ut eros. Nullam id mattis lacus. In eget neque magna, congue imperdiet nulla. Aenean erat lacus, imperdiet a adipiscing non, dignissim eget felis. Nulla facilisi. Vivamus sit amet lorem eget mauris dictum pharetra. In mauris nulla, placerat a accumsan ac, mollis sit amet ligula. Donec eget facilisis dui. Cras elit quam, imperdiet at malesuada vitae, luctus id orci. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque eu libero in leo ultrices tristique. Etiam quis ornare massa. Donec in velit leo. Sed eu ante tortor.</p>\n"
    . "<p>Nullam at tempus enim. Nunc et augue non lectus consequat rhoncus ac a odio. Morbi et tellus eget nisi volutpat tincidunt. Curabitur tristique neque tincidunt purus blandit bibendum. Maecenas eleifend sem quis magna semper id pulvinar nisi porttitor. In in lectus accumsan eros tristique pharetra sit amet ac nulla. Nam vitae felis et orci congue porta nec non ipsum. Donec pretium blandit accumsan. In aliquam lacinia nisi, ut venenatis mauris condimentum ut. Morbi rutrum orci et nisl accumsan euismod. Etiam viverra luctus sem pellentesque suscipit. Aliquam ultricies egestas risus at eleifend. Ut lacinia, tortor non varius malesuada, massa diam aliquet augue, vitae tempor metus tellus eget diam. Nulla vel augue eu elit adipiscing egestas. Duis et nulla est, ac congue arcu. Phasellus semper, ipsum et blandit rutrum, erat ante semper quam, at iaculis quam tellus sed neque.</p>\n"
    . "<p>Pellentesque vulputate sollicitudin justo, at faucibus nisl convallis in. Nulla facilisi. Curabitur nec mauris eu justo ultricies ultricies gravida eu ipsum. Pellentesque at nunc velit, vitae congue nisl. Nam varius imperdiet leo eu accumsan. Nullam elementum fermentum diam euismod porttitor. Etiam sed pellentesque ante. Donec in est elementum mi tempor consectetur. Fusce orci lorem, mollis at tincidunt eget, fringilla sed nunc. Ut consectetur condimentum condimentum. Phasellus sed felis non massa gravida euismod ut in tellus. Curabitur suscipit pharetra sapien vitae dignissim. Morbi id arcu nec ante viverra lobortis vitae nec quam. Mauris id gravida odio. Nunc non sem nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque hendrerit volutpat nisl id elementum. Vivamus lobortis iaculis nisi, sit amet tristique risus porttitor vel. Suspendisse potenti.</p>\n"
    . "<p>Quisque aliquet sapien leo, vitae eleifend dolor. Fusce quis tincidunt nunc. Nam nec purus nulla, ac eleifend lorem. Curabitur eu quam et nibh egestas mattis. Maecenas eget felis augue. Integer scelerisque commodo urna, a pulvinar tortor euismod et. Praesent in nunc sapien. Ut iaculis auctor neque, sit amet rutrum est faucibus vitae. Sed a sagittis quam. Quisque interdum luctus fringilla. Vestibulum vitae nunc in felis luctus ultricies at id magna. Nam volutpat sapien ac lorem interdum pellentesque. Suspendisse faucibus, leo vitae laoreet interdum, mi mi pulvinar neque, sit amet tristique sapien nulla nec dolor. Etiam non ligula augue.</p>\n"
    . "<p>Vivamus purus elit, ornare eget accumsan ut, luctus et orci. Sed vestibulum turpis ut quam vehicula id hendrerit velit suscipit. Pellentesque pulvinar, libero vitae sagittis scelerisque, felis ante faucibus risus, ut viverra velit mi at tortor. Aliquam lacinia condimentum felis, eu elementum ligula laoreet vitae. Sed placerat tempus turpis a fringilla. Etiam porta accumsan feugiat. Phasellus et cursus magna. Suspendisse vitae odio sit amet urna vulputate consectetur. Vestibulum massa magna, sagittis at dictum vitae, sagittis scelerisque erat. Donec viverra tincidunt lacus. Maecenas fermentum erat et mauris tincidunt sed eleifend quam tempus. In at augue mi, in tincidunt arcu. Duis dapibus aliquet mi, ac ullamcorper est semper quis. Sed nec nulla nec odio malesuada viverra id sed nulla. Donec lobortis euismod aliquam. Praesent sit amet dolor quis lacus auctor lobortis. In hac habitasse platea dictumst. Sed at nisi sed nisi ullamcorper pellentesque. Vivamus eget enim sem, non laoreet leo. Sed vel odio lacus.</p>\n"
    . $end;
	$book->addChapter("Chapter 2: Lorem ipsum", "Chapter002.html", $chapter2, true);
	$log->logLine("add ch. 2");
    
    
// Chapter 3
$chapter3 = $start . "<h1>Chapter 3</h1>\n"
    . "<h2>Whatever the Ipsum</h2>\n"
    . "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec magna lorem, mattis sit amet porta vitae, consectetur ut eros. Nullam id mattis lacus. In eget neque magna, congue imperdiet nulla. Aenean erat lacus, imperdiet a adipiscing non, dignissim eget felis. Nulla facilisi. Vivamus sit amet lorem eget mauris dictum pharetra. In mauris nulla, placerat a accumsan ac, mollis sit amet ligula. Donec eget facilisis dui. Cras elit quam, imperdiet at malesuada vitae, luctus id orci. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque eu libero in leo ultrices tristique. Etiam quis ornare massa. Donec in velit leo. Sed eu ante tortor.</p>\n"
    . "<p>Nullam at tempus enim. Nunc et augue non lectus consequat rhoncus ac a odio. Morbi et tellus eget nisi volutpat tincidunt. Curabitur tristique neque tincidunt purus blandit bibendum. Maecenas eleifend sem quis magna semper id pulvinar nisi porttitor. In in lectus accumsan eros tristique pharetra sit amet ac nulla. Nam vitae felis et orci congue porta nec non ipsum. Donec pretium blandit accumsan. In aliquam lacinia nisi, ut venenatis mauris condimentum ut. Morbi rutrum orci et nisl accumsan euismod. Etiam viverra luctus sem pellentesque suscipit. Aliquam ultricies egestas risus at eleifend. Ut lacinia, tortor non varius malesuada, massa diam aliquet augue, vitae tempor metus tellus eget diam. Nulla vel augue eu elit adipiscing egestas. Duis et nulla est, ac congue arcu. Phasellus semper, ipsum et blandit rutrum, erat ante semper quam, at iaculis quam tellus sed neque.</p>\n"
    . $end;
	$book->addChapter("Chapter 3: Whatever the Ipsum", "Chapter003.html", $chapter3);    
	$log->logLine("add ch. 3");

$book->addChapter("Log", "Log.html", $start . $log->getLog() . "\n</pre>" . $end);
if ($book->isLogging) { // Only used in case we need to debug EPub.php.
    $epublog = $book->getLog();
    $book->addChapter("ePubLog", "ePubLog.html", $start . $epublog . "\n</pre>" . $end);
}
    
// Finalize the book, and build the archive.
$book->finalize(); 

// Send the book to the client. ".epub" will be appended if missing.
$zipData = $book->sendBook("Erin_Example_Book $date");

exit;
?>