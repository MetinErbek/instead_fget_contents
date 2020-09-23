<?php 
/**
* Metin ERBEK // metinerbek.com	
*
*/
function get_web_page( $url , $use_proxy = false, $posts = NULL ) {
    if($use_proxy)
	{
		 $json = json_decode(get_web_page('https://api.getproxylist.com/proxy')['content'], TRUE);
		 $proxy_ip = $json['ip'];
		 $proxy_port = $json['port'];
	}
    $res = array();
    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => false,    // do not return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
        CURLOPT_TIMEOUT        => 120,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 
	if(isset($posts))
	{
		$options = array_merge($options, array(
				CURLOPT_POSTFIELDS	   => http_build_query($posts),
		));
	}
	if($use_proxy && isset($proxy_ip) && isset($proxy_port))
	{
		$options = array_merge($options, array(
			CURLOPT_PROXYPORT=> $proxy_port,
			CURLOPT_PROXYTYPE=> 'HTTP',
			CURLOPT_PROXY=> $proxy_ip
		));
	}
	
    $ch      = curl_init( $url ); 

    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 
    $res['content'] = $content;  
	$res['error'] 	= $err;
	$res['errmsg'] 	= $errmsg;
    $res['url'] 	= $header['url'];
	
    return $res; 
}

// Sample
//echo get_web_page('https://www.metinerbek.com', TRUE)['content'];

?>