<?php

    /**
    * Create the parent feed
    */
    $feed = new Zend_Feed_Writer_Feed;
    $feed->setTitle(option('site_title'));
    $feed->setLink(WEB_ROOT);
    $feed->setFeedLink(WEB_ROOT.'/items/browse?output=srss', 'atom');
    $feed->addAuthor(array(
        'name'  => option('site_title'),
        'uri'   => WEB_ROOT,
    ));
    $feed->setDateModified(time());
    $feed->addHub('http://pubsubhubbub.appspot.com/');
     

    /**
    * Create the entries
    */
	foreach( loop( 'items' ) as $item )
	{

		
		// Get the entry data
		$title=  metadata( $item, array( 'Dublin Core', 'Title' ) ) ?
		     metadata( $item, array( 'Dublin Core', 'Title' ) ) :
		     'No title';		     

		$author = srss_authors( metadata( $item, array( 'Dublin Core', 'Creator' ),array('all'=>true) ) );

		$url = WEB_ROOT.'/items/show/'.$item->id;
		
		$continue_link='<p><em><strong>'.__('<a href="%2$s">For more%1$s, view the original article</a>.',srss_media_info($item)['stats_link'], $url).'</em></strong></p>'.srss_footer();
		
		$content='';
		$content=srss_media_info($item,$content)['hero_img']['src'] ? '<img src="'.srss_media_info($item,$content)['hero_img']['src'].'" alt="'.srss_media_info($item,$content)['hero_img']['title'].'" /><br/>' : null;
		$content .= metadata( $item, array( 'Dublin Core', 'Description' )) ? 
			metadata( $item, array( 'Dublin Core', 'Description' )) : 
			'No content';			
		$content=srss_br2p($content).$continue_link;
		
	    
	    // Build the entry
	    $entry = $feed->createEntry();
	    $entry->setTitle($title);
	    $entry->setLink($url);
	    $entry->addAuthor(array(
	        'name'  => $author,
	    ));
	    $entry->setDateModified(strtotime($item->modified));
	    $entry->setDateCreated(strtotime($item->added));
	    //$entry->setDescription(snippet($content,0,500,$continue_link));
	    $entry->setContent($content);
	    
	    $feed->addEntry($entry);
    }
     
    /**
    * Render the resulting feed to Atom 1.0.
    */
    echo $feed->export('atom');