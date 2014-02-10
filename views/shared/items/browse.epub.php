<?php 
// use namespaces
use com\grandt\DublinCore;
use com\grandt\EPub;
use com\grandt\EPubChapterSplitter;
use com\grandt\Logger;
use com\grandt\Zip;


// Logged-in users only!!!!
if(!is_allowed('Items', 'showNotPublic')){
	exit;
}

error_reporting(E_ALL | E_STRICT);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

// include EPub class
$plugin_root=$_SERVER['DOCUMENT_ROOT'].'/plugins/SuperRss';
require_once "$plugin_root/models/EPub/EPub.php";

// site settings and misc. variables
$date = date('l\, jS \of F\, Y h:i:s A');

$site_url = WEB_ROOT;

$parsed_url=parse_url($site_url);

$page_request_url = srss_get_page_url();

$site_title = option('site_title') ? option('site_title') : '[Untitled]';

$site_description = option('description') ? option('description') : '[Description Missing]';

$site_owner = option('author') ? option('author') : $site_title;

$filename = str_replace(' ', '_', $site_title).'-exported_'.time();

// book-related plugin settings and fallbacks
$book_title = get_option('srss_book_title') ? get_option('srss_book_title') : $site_title;

$book_author = get_option('srss_book_author') ? get_option('srss_book_author') : $site_owner;

$book_author_sort = get_option('srss_book_author_sort') ? get_option('srss_book_author_sort') : $book_author;

$book_description = get_option('srss_book_description') ? get_option('srss_book_description') : $site_description;

$book_publisher = get_option('srss_book_publisher') ? get_option('srss_book_publisher')  : $site_title;

$book_publisher_url = get_option('srss_book_publisher_url') ? get_option('srss_book_publisher_url') : $site_url;

$book_rights = get_option('srss_book_rights') ? get_option('srss_book_rights') : date('Y').' '.$site_owner;

$book_subjects = get_option('srss_book_subjects') ? explode( '|', get_option('srss_book_subjects') ) : null;

$book_cover_img = ( ( get_option('srss_book_cover_image_url') ) && ( exif_imagetype( get_option('srss_book_cover_image_url') ) == IMAGETYPE_JPEG ) ) ? get_option('srss_book_cover_image_url') : "$plugin_root/views/shared/items/assets/cover-image_blank.jpg";


$introText = get_option('srss_book_intro') ? get_option('srss_book_intro') : false;

$conclusionText = get_option('srss_book_conclusion') ? get_option('srss_book_conclusion') : false;

$resourcesText = get_option('srss_book_resources') ? get_option('srss_book_resources') : false;

// set up logging
// date_default_timezone_set('America/New_York');
// require_once "$plugin_root/models/EPub/Logger.php";
// $log = new Logger("My Log", TRUE);

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
// $log->logLine("instantiate epub class");

// Title
$book->setTitle($book_title);
// $log->logLine("set title");

// Identifier
$book->setIdentifier($page_request_url, EPub::IDENTIFIER_URI); 
// $log->logLine("set identifier");

// Language
$book->setLanguage("en"); 
// $log->logLine("set language");

// Description
$book->setDescription($book_description);
// $log->logLine("set description");

// Author
$book->setAuthor($book_author, $book_author_sort);
// $log->logLine("set author and author sort key");

// Publisher
$book->setPublisher($book_publisher, $book_publisher_url); 
// $log->logLine("set publisher");

// Date
$book->setDate($date); 
// $log->logLine("set date");

// Rights statement
$book->setRights($book_rights); 
// $log->logLine("set rights");

// Source
$book->setSourceURL($site_url);
// $log->logLine("set source");


// Keyword/subject metadata
if(is_array($book_subjects)){
	foreach($book_subjects as $subject){
		$book->setSubject($subject);
	}
}
// $log->logLine("set subjects");


// CSS
$cssData = "
/* Basic */

body {
  font-family: serif;
}

h1, h2, h3, h4, h5, h6 {
  font-family: Arial, Helvetica, sans-serif;
  text-align: left;
}

h1{
  text-align: left;
  text-transform: uppercase;
  font-size: 1.25em;
}


h2{
  font-style:italic;
  text-align: left;
  font-size: 1.1em;
  letter-spacing: 1px;  
}


h3{
  font-size: 1.0em;
  font-weight: 700;
  text-align: left;
  margin-bottom: .8em;
}

h4 {
  font-size: 1em;
  font-weight: bold;
  color: #666;
  text-align: left;
  padding-top: 1em;
  margin-bottom: .8em;
}


blockquote {
  font-family: Arial, Helvetica, sans-serif;
  font-style: italic;
  font-weight: 400;
  line-height: 1.2em;
  margin: 0 0 0.45em 0.9em;
  text-align: left;
  text-indent: 0;
}

sup {
  vertical-align: super;
  font-size: 70%;
  line-height: 100% !important;
}

a {
  font-family: Arial, Helvetica, sans-serif;
  font-style: normal;
  font-weight: 400;
}


img {
  max-width: 100%;
  max-height: 15em;
}

/* Specific */

.book_generated,.book_title,.book_author{
	text-align:left;
}

h3.ch_author{
	font-family:serif;
	font-size:0.75em;
}

h2.ch_subtitle{
	font-size:0.9em;
}

h1.ch_title, h2.ch_subtitle, h3.ch_author{
	text-align:right;
}

h2.ch_subtitle, h3.ch_author{
	text-transform: uppercase;
	font-weight:lighter;
	margin:0 0.5em 1em;
}

div.ch_img{
	text-align:right;
	display:block;
	clear:both;
	margin-bottom:1em;
}

div.ch_continue{
	font-size:0.85em;
	font-style:italic;
}

/*Kindle Legacy Specific Styles*/
@media amzn-mobi {


  h1 {
    font-size: 200%;
  }

  h2 {
    font-family: serif;
    text-transform: none;
    font-size: 150%;
    font-weight: normal;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  h3 {
    font-family: serif;
    font-size: 125%;
    font-weight: normal;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  h4 {
    font-family: serif;
    font-size: 120%;
    font-weight: normal;
    font-style: italic;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  h2 {
    font-family: serif;
    text-transform: none;
    font-size: 150%;
    font-weight: normal;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  h3 {
    font-family: serif;
    font-size: 125%;
    font-weight: normal;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  h4 {
    font-family: serif;
    font-size: 120%;
    font-weight: normal;
    font-style: italic;
    margin-top: 1em;
    margin-bottom: 1em;
  }

  .sub-entry {
    text-indent: 20%;
  }

  p.body-first-paragraph {
    text-indent: 0;
  }
}
";

$book->addCSSFile("styles.css", "css1", $cssData);
// $log->logLine("add css");

// Cover image
$book->setCoverImage("Cover.jpg", file_get_contents($book_cover_img), "image/jpeg");
// $log->logLine("set cover image");

// Title page
$titlePage = $start . "<h1>$book_title</h1>\n<h2>By $book_author</h2>\n<small>Generated at ".$parsed_url['host']." on $date.</small>" . $end;
$book->addChapter("Title Page", "TitlePage.html", $titlePage);
// $log->logLine("add title page");

// TOC
$book->buildTOC(NULL, "toc", "Table of Contents", TRUE, TRUE);
// $log->logLine("add TOC");
// Intro
if($introText){
	$book->addChapter("Introduction","Introduction.html",$start.'<h1 class="ch_title">Introduction</h1>'.$introText.$end,true,EPub::EXTERNAL_REF_IGNORE);
}

// Build the chapters
$chapterIndex=1;
foreach( loop( 'items' ) as $item ){

	if( ($item->public) == 1 ){
		
		$chapterIndexPadded=str_pad($chapterIndex, 5, "0", STR_PAD_LEFT);
		$chapterTitle=metadata($item,array('Dublin Core','Title'), array('index'=>0));
		
		$srss_media_info=srss_media_info($item);
		$url = WEB_ROOT.'/items/show/'.$item->id;
		$continue_link= (get_option('srss_include_read_more_link')==1) ? '<p><em><strong>'.__('<a href="%2$s">For more%1$s, view the original article</a>.',$srss_media_info['stats_link'], $url).'</strong></em></p>' : null;
	
		$content='';
		$content=$srss_media_info['hero_img']['src'] ? '<img src="'.$srss_media_info['hero_img']['src'].'" alt="'.$srss_media_info['hero_img']['title'].'" /><br/>' : null;
		$content .= metadata( $item, array( 'Dublin Core', 'Description' ));

		
		
		$text = $start;	
		$text .= '<h1 class="ch_title">'.$chapterTitle.'</h1>';
		$text .= ( metadata($item, array('Dublin Core', 'Title'), array('index'=>1))!==('[Untitled]') ) ? '<h2 class="ch_subtitle">'.metadata($item, array('Dublin Core', 'Title'), array('index'=>1)).'</h2>' : null;
		$text .= ($authors=metadata( $item, array( 'Dublin Core', 'Creator' ),array('all'=>true))) ? '<h3 class="ch_author">'.srss_authors($authors).'</h3>' : null;
		$text .= '<div class="ch_content">'.srss_br2p($content).$continue_link.'</div>';
		$text .= $end;
		
	    
		$book->addChapter("Chapter $chapterIndex: $chapterTitle", "Chapter$chapterIndexPadded.html", $text, true);
		unset($text);
		$chapterIndex++;
		// $log->logLine("add ch. 2");
			
	}
}  

// Conclusion
if($conclusionText){
	$book->addChapter("Conclusion","Conclusion.html",$start.'<h1 class="ch_title">Conclusion</h1>'.$conclusionText.$end,true,EPub::EXTERNAL_REF_IGNORE);
	// $log->logLine("add Conclusion");
}

// Resources
if($resourcesText){
	$book->addChapter("Resources","Resources.html",$start.'<h1 class="ch_title">Resources</h1>'.$resourcesText.$end,true,EPub::EXTERNAL_REF_IGNORE);
	// $log->logLine("add Resources");
}

// $book->addChapter("Log", "Log.html", $start . $log->getLog() . "\n</pre>" . $end);
// if ($book->isLogging) { // Only used in case we need to debug EPub.php.
//     $epublog = $book->getLog();
//     $book->addChapter("ePubLog", "ePubLog.html", $start . $epublog . "\n</pre>" . $end);
// }
    
// Finalize the book, and build the archive.
$book->finalize(); 

// Send the book to the client. ".epub" will be appended if missing.
$zipData = $book->sendBook($filename);

exit;
?>