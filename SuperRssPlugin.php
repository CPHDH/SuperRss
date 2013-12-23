<?php

class SuperRssPlugin extends Omeka_Plugin_AbstractPlugin
{
   protected $_filters = array(
      'response_contexts',
      'action_contexts' );

   public function filterResponseContexts( $contexts )
   {
      $contexts['srss'] = array(
         'suffix' => 'srss',
         'headers' => array( 'Content-Type' => 'text/xml' ) );
      return $contexts;
   }

   public function filterActionContexts( $contexts, $args ) {
      $controller = $args['controller'];

      if( is_a( $controller, 'ItemsController' ) )
      {
         $contexts['browse'][] = 'srss' ;
         //$contexts['show'][] = 'srss' ;
      }

      return $contexts;
   }
}
function srss_oxfordComma($items=null) {
    $count = count($items);

    if($count === 0) {
        return null;
    } else if($count === 1) {
        return $items[0];
    } else {
        return implode(' , ', array_slice($items, 0, $count - 1)) . ' and ' . $items[$count - 1];
    }
}

function srss_br2p($data) {
    $data = preg_replace('#(?:<br\s*/?>\s*?){2,}#', '</p><p>', $data);
    return "<p>$data</p>";
}