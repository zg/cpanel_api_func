<?php
function cpanel_api_func($module,$function,$params,$apiversion,$return_type)
{
	$cpanel_username = 'test';
	$cpanel_password = 'test';

	if(!isset($module))
	{
		return 'Unspecified module.';
	}
	if(!isset($function))
	{
		return 'Unspecified function.';
	}
	if(!is_array($params))
	{
		return 'Params must be an array.';
	}
	if(!is_numeric($apiversion))
	{
		return "API version isn't numeric.";
	}
	if(!($return_type == 'xml' || $return_type == 'json'))
	{
		return "Invalid return type ('xml' or 'json' are accepted).";
	}

	$query = 'https://127.0.0.1:2083/'.$return_type.'-api/cpanel?user='.$cpanel_username.'&cpanel_'.$return_type.'api_module='.$module.'&cpanel_'.$return_type.'api_func='.$function.'&cpanel_'.$return_type.'api_version='.$apiversion.'&';

	foreach($params as $key => $value)
	{
		$query .= '&'.urlencode($key).'='.urlencode($value);
	}

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$header[0] = "Authorization: Basic " . base64_encode($cpanel_username.":".$cpanel_password) . "\n\r";
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_URL, $query);
	$result = curl_exec($curl);
	if($result == false)
	{
		return 'curl_exec threw error "'.curl_error($curl).'" for $query';
	}
	curl_close($curl);

	return ($return_type == 'xml' ? new SimpleXMLElement($result) : json_decode($result));
}
?>