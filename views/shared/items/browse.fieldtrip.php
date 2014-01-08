<?php

// create simplexml object
$xml = new SimpleXMLElement('<rss version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:fieldtrip="http://www.fieldtripper.com/fieldtrip_rss"></rss>');
$NS = array( 
    'georss' => 'http://www.georss.org/georss',
    'fieldtrip'=> 'http://www.fieldtripper.com/fieldtrip_rss'
); 
// add channel information
$xml->addChild('channel');
$xml->channel->addChild('title', option('site_title'));
$xml->channel->addChild('link', WEB_ROOT);
$desc=get_theme_option('about') ? get_theme_option('about') : (option('description') ? option('description') : "Description is unavailable.");
$xml->channel->addChild('description', $desc );
if(get_theme_option('apple_icon_144')!=null){
	$xml->channel->addChild('image',get_theme_option('apple_icon_144'));
}
$xml->channel->addChild('pubDate', date(DateTime::RSS));
if(option('administrator_email')!=null){
	$xml->channel->addChild('managingEditor',option('administrator_email'));
}

// query database for article data
foreach( loop( 'items' ) as $item ) {
    // add item element for each article
    $entry = $xml->channel->addChild('item');

	// Get the entry data
	$title=  metadata( $item, array( 'Dublin Core', 'Title' ) ) ?
	     metadata( $item, array( 'Dublin Core', 'Title' ) ) :
	     'No title';		     

	$author = srss_authors( metadata( $item, array( 'Dublin Core', 'Creator' ),array('all'=>true) ) );

	$url = WEB_ROOT.'/items/show/'.$item->id;
	
	$continue_link='<p><em><strong>'.__('<a href="%2$s">For more%1$s, view the original article</a>.',srss_media_info($item)['stats_link'], $url).'</em></strong></p>'.srss_footer();
	
	$content='';
	$content=srss_media_info($item,$content)['hero_img'] ? srss_media_info($item,$content)['hero_img'].'<br/>' : null;
	$content .= metadata( $item, array( 'Dublin Core', 'Description' )) ? 
		metadata( $item, array( 'Dublin Core', 'Description' )) : 
		'No content';			
	$content=srss_br2p($content).$continue_link;    
    
   
   // Build the entry
    $entry->addChild('title',$title);
    $entry->addChild('guid',$item->id);
    $entry->addChild('description', $content);
    $entry->addChild('link', $url);
	$entry->addChild('pubDate', strtotime($item->modified) );
	$entry->addChild('author', $author);
	$entry->addChild('point', srss_GeoRSSPoint($item),$NS['georss']);
}

// output xml
header('Content-Type: application/xhtml+xml');
echo $xml->asXML();