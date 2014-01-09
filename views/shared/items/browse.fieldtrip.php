<?php
// Extend SimpleXMLElement to more easily use CDATA
// http://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this); 
    $no   = $node->ownerDocument; 
    $node->appendChild($no->createCDATASection($cdata_text)); 
  } 
}

// create simplexml object
$xml = new SimpleXMLExtended('<rss version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:fieldtrip="http://www.fieldtripper.com/fieldtrip_rss"></rss>');
$NS = array(
	'georss' => 'http://www.georss.org/georss',
	'fieldtrip'=> 'http://www.fieldtripper.com/fieldtrip_rss',
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

// get feed item data
foreach( loop( 'items' ) as $omeka_item ) {
	// add item element for each article
	$feed_item = $xml->channel->addChild('item');

	// Get the entry data
	$title=  metadata( $omeka_item, array( 'Dublin Core', 'Title' ) ) ?
		metadata( $omeka_item, array( 'Dublin Core', 'Title' ) ) :
		'No title';

	$author = srss_authors( metadata( $omeka_item, array( 'Dublin Core', 'Creator' ),array('all'=>true) ) );

	$url = WEB_ROOT.'/items/show/'.$omeka_item->id;

	$continue_link='<p><em><strong>'.__('<a href="%2$s">For more%1$s, view the original article</a>.',srss_media_info($omeka_item)['stats_link'], $url).'</em></strong></p>'.srss_footer();

	$content='';
	$content .= metadata( $omeka_item, array( 'Dublin Core', 'Description' )) ?
		metadata( $omeka_item, array( 'Dublin Core', 'Description' )) :
		'No content';
	$content=srss_br2p($content).$continue_link;

	// Build the feed item
	$feed_item->addChild('title',$title);
	$feed_item->addChild('guid',$omeka_item->id);
	$feed_item->addChild('description', $content);
	$feed_item->addChild('link', $url);
	$feed_item->addChild('pubDate', strtotime($omeka_item->modified) );
	$feed_item->addChild('author', $author);
	if($point=srss_GeoRSSPoint($omeka_item)){
		$feed_item->addChild('point', $point, $NS['georss']);
	}
	if($img_src=srss_media_info($omeka_item,$content)['hero_img']['src']){

		$feed_item_image = $xml->channel->item->addChild('image', '', $NS['fieldtrip']);
		$feed_item_image->addChild('url',$img_src);

		if($img_caption=strip_tags(srss_media_info($omeka_item,$content)['hero_img']['title'])){
			$feed_item_image->addChild('title',$img_caption);
		}
	}

}

// output xml
header('Content-Type: application/xhtml+xml');
echo $xml->asXML();