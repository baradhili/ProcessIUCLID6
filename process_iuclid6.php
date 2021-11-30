<?php
include('./xml2json.php');

$arrayData = [];
$xmlOptions = array(
    "namespaceRecursive" => "True"
);

function &i6cArray(& $array){
    foreach ($array as $key => $value) {
        if(is_array($value)){
            //recurse the array of arrays
            $value = &i6cArray($value);
            $array[$key]=$value;
            print_r($value);
        } elseif ($key == '@xlink:href') {
            // we want to replace the element here with the ref'd file contents
            // So we should get name.content = file contents
            $tempxml = simplexml_load_file($value);
            $tempArrayData = xmlToArray($tempxml);
            $array['content']=$tempArrayData;
        } elseif ($key == 'country') {
            //fix encodings - country
        } elseif ($key == 'unitCode') {
            //fix encodings - units
        } elseif ($key == 'value') {
            //fix encodings - general values
        }
    }
    return $array;
}

if (file_exists('manifest.xml')) {
    $xml = simplexml_load_file('manifest.xml');
    $arrayData = xmlToArray($xml,$xmlOptions);
    
    // walk array - we know the initial thing is an array
    $arrayData = &i6cArray($arrayData);
    
    //output result
    $jsonString = json_encode($arrayData, JSON_PRETTY_PRINT);
    file_put_contents('dossier.json', $jsonString);
} else {
    exit("Failed to open manifest.");
}

?>