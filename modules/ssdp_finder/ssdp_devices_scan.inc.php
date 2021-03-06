<?php
/*
* @version 0.1 (wizard)
*/

require('upnp/vendor/autoload.php');
use jalder\Upnp\Upnp;


global $session;
if ($this->owner->name == 'panel') {
    $out['CONTROLPANEL'] = 1;
}
$qry = "1";
// search filters
// QUERY READY
global $save_qry;
if ($save_qry) {
    $qry = $session->data['ssdp_devices_qry'];
} else {
    $session->data['ssdp_devices_qry'] = $qry;
}
if (!$qry) $qry = "1";
//="ID DESC";
//$out['SORTBY']=$sortby_ssdp_devices;
// SEARCH RESULTS
$res = Scan();
if ($res[0]['UUID']) {
    //paging($res, 100, $out); // search result paging
    $total = count($res);
    for ($i = 0; $i < $total; $i++) {
        // some action for every record if required
        $tmp = explode(' ', $res[$i]['UPDATED']);
        $res[$i]['UPDATED'] = fromDBDate($tmp[0]) . " " . $tmp[1];
    }
    $out['RESULT'] = $res;
}

function Scan()
{
	$upnp = new Upnp();
    #print('searching...' . PHP_EOL);
    $everything = $upnp->discover();
    $result = [];
    $table_name='ssdp_devices';

    foreach ($everything as $device) {
        $control_url = str_ireplace("Location:", "", $device['location']);
	$xml=simplexml_load_file($control_url);
        $uuid = $xml->device->UDN;
        $existed = $rec=SQLSelectOne("SELECT * FROM $table_name WHERE UUID='$uuid'");
        // print array_search(, array_column( $result, 'ADDRESS'));
        if (!array_search_result($result, 'CONTROLADDRESS', $control_url) && !is_null($uuid)) {
            $result[] = [
                "ID" => $existed["ID"], //existed id Majordomo
                "TITLE" => $xml->device->friendlyName,//friendly name
                "ADDRESS" => getIp($control_url,false) ,//presentation url (web UI of device),//presentation url (web UI of device)
                "UUID" => $xml->device->UDN,
                "DESCRIPTION" => $xml->device->modelDescription.$device['server'],//description get from xml or field "server"
                "TYPE" => explode(":", $xml->device->deviceType)[3],//DeviceType
                "LOGO" => getDefImg($control_url,$xml),//Logo 
                "SERIAL" => $xml->device->serialNumber,//serialnumber
                "MANUFACTURERURL" => $xml->device->manufacturerURL,//manufacturer url
                "UPDATED" => '',
                "MODEL" => $xml->device->modelName,//model
                "MODELNUMBER" => $xml->device->modelNumber,//modelNumber
                "MANUFACTURER" => $xml->device->manufacturer,//Manufacturer
                "SERVICES"=> getServices($xml),//list services of device
		"CONTROLADDRESS"=> $control_url,//list services of device
            ];
        }
    }
    /*
    print("<pre>");
    print_r($result);
     print("</pre>");
    */
    return $result;
}

function array_search_result($array, $key, $value)
{
    //  global $result;
    foreach ($array as $k => $v) {
        if (array_key_exists($key, $v) && ($v[$key] == $value)) {
            return true;
        }
    }
    // return $result;;
}


function getIp($baseUrl,$withPort)
{
	if( !empty($baseUrl) ){
        $parsed_url = parse_url($baseUrl);
        if($withPort ==true){
            $baseUrl = $parsed_url['scheme'].'://'.$parsed_url['host'].':'.$parsed_url['port'];
        }else{
            $baseUrl = $parsed_url['host'];
        }
    }
    
    return  $baseUrl;
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function getServices($xml){
	$result = array();
	foreach($xml->device->serviceList->service as $type)
    {
        $name = explode(":", $type->serviceType)[3];
		array_push($result,$name);
    }
	return implode(",",$result);
}
function endsWith($haystack, $needle)
{
	return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function SearchArray($array, $searchIndex, $searchValue)
{
    if (!is_array($array) || $searchIndex == '')
        return false;

    foreach ($array as $k => $v)
    {
        if (is_array($v) && array_key_exists($searchIndex, $v) && $v[$searchIndex] == $searchValue)
            return $k;
    }

    return false;
}

function getDefImg($control_url,$xml)
{
    $baseUrl = getIp($control_url,True);
    if (!$xml->device->iconList->icon){
        return "/templates/ssdp_finder/img/".explode(":", $xml->device->deviceType)[3]. ".png";//"Icons not found..."
    } else {
        foreach ($xml->device->iconList->icon as $icon) {
	    if ($icon->with = 48){
	        $url = $icon->url;
	        break;
	    } else if ($with < $icon->with) {
	        $url = $icon->url;
	        $with = $icon->with;
	    } else {
	        $url = $icon->url;}
	    }    
	        return $baseUrl.$url;
    }

    
}
