<?php

require_once dirname(__FILE__) . '/helpers/SuperRssFunctions.php';


class SuperRssPlugin extends Omeka_Plugin_AbstractPlugin
{
    
    const DEFAULT_FB_LINK = null;
    const DEFAULT_TWITTER_USERNAME = null;
    const DEFAULT_YOUTUBE_USERNAME = null;
    const DEFAULT_IOS_APP_ID = null;
    const DEFAULT_ANDROID_APP_ID = null;
    const DEFAULT_READ_MORE = 1;
    const DEFAULT_READ_MORE_STATS = 1;
    const DEFAULT_SOCIAL_MEDIA_LINKS = 1;
    const DEFAULT_APP_STORE_LINKS = 0;
    const DEFAULT_ABOUT_TEXT = null;
    const DEFAULT_IMAGE_URL = null;
    const DEFAULT_BOOK_COVER_IMAGE = null;
    const DEFAULT_BOOK_SUBJECTS = null;
    const DEFAULT_BOOK_RIGHTS = null;
    const DEFAULT_BOOK_PUBLISHER = null;
    const DEFAULT_BOOK_PUBLISHER_URL = null;
    const DEFAULT_BOOK_DESCRIPTION = null;
    const DEFAULT_BOOK_AUTH = null;
    const DEFAULT_BOOK_AUTH_SORT = null;
    const DEFAULT_BOOK_TITLE = null;   
    const DEFAULT_BOOK_INTRO = null;
    const DEFAULT_BOOK_CONCLUSION = null;
    const DEFAULT_BOOK_RESOURCES = null;
    const DEFAULT_BOOK_ABOUT_CONTRIBUTORS = null;
    const DEFAULT_BOOK_INCLUDE_CONTRIBUTORS = 1;

    protected $_hooks = array(
    	'install', 
    	'uninstall',
        'config_form', 
        'config');

	protected $_filters = array(
		'response_contexts',
		'action_contexts' );


    protected $_options = array(
        'srss_facebook_link' => self::DEFAULT_FB_LINK,
        'srss_twitter_user' => self::DEFAULT_TWITTER_USERNAME,
        'srss_youtube_user' => self::DEFAULT_YOUTUBE_USERNAME,
        'srss_ios_id' => self::DEFAULT_IOS_APP_ID,
        'srss_android_id' => self::DEFAULT_ANDROID_APP_ID,
        'srss_include_read_more_link' => self::DEFAULT_READ_MORE,
        'srss_include_mediastats_footer' => self::DEFAULT_READ_MORE_STATS,
        'srss_include_social_footer' => self::DEFAULT_SOCIAL_MEDIA_LINKS,
        'srss_include_applink_footer' => self::DEFAULT_APP_STORE_LINKS,
        'srss_about_text' => self::DEFAULT_ABOUT_TEXT,
        'srss_image_url' => self::DEFAULT_IMAGE_URL,
        'srss_book_cover_image_url' => self::DEFAULT_BOOK_COVER_IMAGE,
        'srss_book_subjects' => self::DEFAULT_BOOK_SUBJECTS,
        'srss_book_rights' => self::DEFAULT_BOOK_RIGHTS,
        'srss_book_publisher' => self::DEFAULT_BOOK_PUBLISHER,
        'srss_book_publisher_url' => self::DEFAULT_BOOK_PUBLISHER_URL,
        'srss_book_description' => self::DEFAULT_BOOK_DESCRIPTION,
        'srss_book_author' => self::DEFAULT_BOOK_AUTH,
        'srss_book_author_sort' => self::DEFAULT_BOOK_AUTH_SORT,
        'srss_book_title' => self::DEFAULT_BOOK_TITLE,
        'srss_book_about_contributors' => self::DEFAULT_BOOK_ABOUT_CONTRIBUTORS,
        'srss_book_resources' => self::DEFAULT_BOOK_RESOURCES,
        'srss_book_conclusion' => self::DEFAULT_BOOK_CONCLUSION,
        'srss_book_intro' => self::DEFAULT_BOOK_INTRO,
        'srss_book_include_contributors' => self::DEFAULT_BOOK_INCLUDE_CONTRIBUTORS
    );


	public function filterResponseContexts( $contexts )
	{
		$contexts['srss'] = array(
			'suffix' => 'srss',
			'headers' => array( 'Content-Type' => 'text/xml' )
		);
		$contexts['fieldtrip'] = array(
			'suffix' => 'fieldtrip',
			'headers' => array( 'Content-Type' => 'text/xml' )
		);
		$contexts['epub'] = array(
			'suffix' => 'epub',
			'headers' => array( 'Content-Type' => 'text/xml' )
		);		
		return $contexts;
	}

	public function filterActionContexts( $contexts, $args ) {
		$controller = $args['controller'];

		if( is_a( $controller, 'ItemsController' ) )
		{
			$contexts['browse'][] = 'srss' ;
			$contexts['browse'][] = 'fieldtrip' ;
			$contexts['browse'][] = 'epub' ;
		}

		return $contexts;
	}

        
    /*
    ** Plugin options
    */
    
    public function hookConfigForm()
    {
        require dirname(__FILE__) . '/config_form.php';
    }	
        
    public function hookConfig()
    {
        set_option('srss_facebook_link', $_POST['srss_facebook_link']);
        set_option('srss_twitter_user', $_POST['srss_twitter_user']);
        set_option('srss_youtube_user', $_POST['srss_youtube_user']);
        set_option('srss_ios_id', $_POST['srss_ios_id']);
        set_option('srss_android_id', $_POST['srss_android_id']);
        set_option('srss_about_text', $_POST['srss_about_text']);
        set_option('srss_image_url', $_POST['srss_image_url']);
        set_option('srss_include_social_footer', (int)(boolean)$_POST['srss_include_social_footer']);
        set_option('srss_include_applink_footer', (int)(boolean)$_POST['srss_include_applink_footer']);
        set_option('srss_include_read_more_link', (int)(boolean)$_POST['srss_include_read_more_link']);
        set_option('srss_include_mediastats_footer', (int)(boolean)$_POST['srss_include_mediastats_footer']);     
        set_option('srss_book_title', $_POST['srss_book_title']);
        set_option('srss_book_author', $_POST['srss_book_author']);
        set_option('srss_book_author_sort', $_POST['srss_book_author_sort']);
        set_option('srss_book_description', $_POST['srss_book_description']);
        set_option('srss_book_publisher', $_POST['srss_book_publisher']);
        set_option('srss_book_publisher_url', $_POST['srss_book_publisher_url']);
        set_option('srss_book_rights', $_POST['srss_book_rights']);
        set_option('srss_book_subjects', $_POST['srss_book_subjects']);
        set_option('srss_book_cover_image_url', $_POST['srss_book_cover_image_url']);      
        set_option('srss_book_intro', $_POST['srss_book_intro']);
        set_option('srss_book_conclusion', $_POST['srss_book_conclusion']);
        set_option('srss_book_resources', $_POST['srss_book_resources']);
        set_option('srss_book_about_contributors', $_POST['srss_book_about_contributors']);
        set_option('srss_book_include_contributors', (int)(boolean)$_POST['srss_book_include_contributors']);
        
    }	
    

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {		
		$this->_installOptions();    
    
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {        
		$this->_uninstallOptions();	
		
    }	
}