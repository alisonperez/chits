<?php
class DataNode {
    var $name;      // node name
    var $code;      // three digit code
    var $telephone; // node phone
    var $level;     // self, parent, child

    function DataNode () {
    }

    function parseXML($mvalues) {
        for ($i=0; $i < count($mvalues); $i++)
            $node[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
        return ($node);
    }

    function readConfig($filename) {
    // read the xml database of aminoacids
        $data = implode("",file($filename));
        $parser = xml_parser_create();
        xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
        xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
        xml_parse_into_struct($parser,$data,$values,$tags);
        xml_parser_free($parser);

        // loop through the structures
        foreach ($tags as $key=>$val) {
            if ($key == "node") {
                $noderanges = $val;
                // each contiguous pair of array entries are the
                // lower and upper range for each node definition
                for ($i=0; $i < count($noderanges); $i+=2) {
                    $offset = $noderanges[$i] + 1;
                    $len = $noderanges[$i + 1] - $offset;
                    $tdb[] = $this->parseXML(array_slice($values, $offset, $len));
                }
            } else {
                continue;
            }
        }
        return $tdb;
    }
}
$datanode = new DataNode;
$db = $datanode->readConfig("../config.xml");
$_SESSION["datanode"] = array("self"=>"","code"=>"", "telephone"=>"", "name"=>"");

foreach ($db as $key=>$value) {
    if ($db["$key"]["level"]=="self") {
        $_SESSION["datanode"]["code"] = $db[$key]["code"];
        $_SESSION["datanode"]["telephone"] = $db[$key]["telephone"];
        $_SESSION["datanode"]["name"] = $db[$key]["name"];    
    }
}
?>
