<?php

require_once dirname(__FILE__) . '/helpers/SuperRssFunctions.php';


class SuperRssPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_filters = array(
		'response_contexts',
		'action_contexts' );

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
		return $contexts;
	}

	public function filterActionContexts( $contexts, $args ) {
		$controller = $args['controller'];

		if( is_a( $controller, 'ItemsController' ) )
		{
			$contexts['browse'][] = 'srss' ;
			$contexts['browse'][] = 'fieldtrip' ;
		}

		return $contexts;
	}
}