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
     

	// Get some info for the entry footer
	// For now, we'll use known settings for the Curatescape theme
	$footer=null;
	if( ($ios=get_theme_option('ios_app_id')) || ($adk=get_theme_option('android_app_id')) ){
		$app_store=array();
		isset($adk) ? $app_store[]='<a href="http://play.google.com/store/apps/details?id='.$adk.'">Android</a>' : null;
		isset($ios) ? $app_store[]='<a href="https://itunes.apple.com/us/app/'.$ios.'">iPhone</a>' : null;
		$footer.='<br><small>'.__('Download the app for %s', implode($app_store, ' and ')).'</small>';
	}

	if( ($fb=get_theme_option('facebook_link')) || ($tw=get_theme_option('twitter_username')) || ($yt=get_theme_option('youtube_username')) ){
		$soial=array();
		isset($fb) ? $social[]='<a href="'.$fb.'">Facebook</a>' : null;
		isset($tw) ? $social[]='<a href="https://twitter.com/'.$tw.'">Twitter</a>' : null;
		isset($yt) ? $social[]='<a href="http://www.youtube.com/user/'.$yt.'">Youtube</a>' : null;
		$footer.='<br><small>'.__('Find us on %s', srss_oxfordComma($social)).'</small>';
	}

	// Create the entries
	foreach( loop( 'items' ) as $item )
	{

		$title=  metadata( $item, array( 'Dublin Core', 'Title' ) ) ?
		     metadata( $item, array( 'Dublin Core', 'Title' ) ) :
		     'No title';		     

		
		$authors = metadata( $item, array( 'Dublin Core', 'Creator' ),array('all'=>true) );
		if(count($authors)>0){
			$all_authors=array();
			foreach($authors as $author){
				$all_authors[]=$author;
			}
			$author=srss_oxfordComma($all_authors);
		}else{
			$author='The '.option('site_title').' Team';
		}

				
		//$author_url=WEB_ROOT.'/items/browse?search=&advanced[0][element_id]=39&advanced[0][type]=is+exactly&advanced[0][terms]='.urlencode($authors).'&submit_search=Search';	
					
		$content = metadata( $item, array( 'Dublin Core', 'Description' )) ? 
			metadata( $item, array( 'Dublin Core', 'Description' )) : 
			'No content';
		$content=srss_br2p($content);	
			
		$url = WEB_ROOT.'/items/show/'.$item->id;	
				
		$article_link="<br><a href=\"$url\">Continue Reading</a>";		

		// Item files
		$files=array();
		$images=array();
		$audio=array();
		$video=array();
		foreach( $item->Files as $file )
		{
		       $path = $file->getWebPath( 'original' );
		    
		       $mimetype = metadata( $file, 'MIME Type' );
		       $filedata = array(
		          'id'        => $file->id,
		          'mime-type' => $mimetype,
		          );
		    
		       if( $ftitle = metadata( $file, array( 'Dublin Core', 'Title' ) ) ) {
		          $filedata['title'] = strip_formatting( $ftitle );
		       }
		    
		    
		       if( $description = metadata( $file, array( 'Dublin Core', 'Description' ) ) ) {
		          $filedata['description'] = $description;
		       }
		    
		       if( $file->hasThumbnail() ) {
		          $filedata['thumbnail'] = $file->getWebPath( 'square_thumbnail' );
		          $filedata['fullsize'] = $file->getWebPath( 'fullsize' );
		       }
		       
		       if( strpos($filedata['mime-type'], 'image/' )===0){
			       $images[]=$filedata;
		       }

		       if( strpos($filedata['mime-type'], 'audio/' )===0){
			       $audio[]=$filedata;
		       }

		       if( strpos($filedata['mime-type'], 'video/' )===0){
			       $video[]=$filedata;
		       }		       
			   			   

		}
		
	   $fstr=array();
	   if( count($images) >0 ){
	   	   $num=count($images);
		   $hero='<img alt="'.$images[0]['title'].'" src="'.$images[0]['fullsize'].'"/>';
		   $content="$hero<br>$content";
		   $fstr[]=$num.' '.($num > 1 ? __('images') : __('image') );
	   }
	   
	   if( count($audio) >0 ){
		   $num=count($audio);
		   $fstr[]=$num.' '.($num > 1 ? __('sound clips') : __('sound clip') );
	   }
	   
	   if( count($video) >0 ){
		   $num=count($video);
		   $fstr[]=$num.' '.($num > 1 ? __('videos') : __('video') );
	   }
	   
	   $item_file_stats= count($fstr) > 0 ? __(' (including %s)', srss_oxfordComma($fstr)) : null;



		$content.='<p><em><strong>'.__('<a href="%2$s">For more%1$s, view the original article</a>.',$item_file_stats, $url).'</em></strong></p>'.$footer;
		

		
		// Location data
		if( $location = get_db()->getTable( 'Location' )->findLocationByItem( $item, true ) )
		{
		    $lat = $location['latitude'];
		    $lon = $location['longitude'];
		    //$content.='<p><a href="https://www.google.com/maps/preview#!q='.$lat.','.$lon.'">'.__('View Location in Google Maps').'</a></p>';
		}   

	    
	    // Build the entry
	    $entry = $feed->createEntry();
	    $entry->setTitle($title);
	    $entry->setLink($url);
	    $entry->addAuthor(array(
	        'name'  => $author,
	    //    'uri'   => $author_url,
	    ));
	    $entry->setDateModified(strtotime($item->modified));
	    $entry->setDateCreated(strtotime($item->added));
	    //$entry->setDescription(snippet($content,0,500,$article_link));
	    $entry->setContent($content);
	    $feed->addEntry($entry);
    }
     
    /**
    * Render the resulting feed to Atom 1.0.
    */
    echo $feed->export('atom');