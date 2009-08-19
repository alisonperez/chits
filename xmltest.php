<?php
class DataNode {
    var $name;      // node name
    var $code;      // three digit code
    var $telephone; // node phone
    var $level;     // self, parent, child

    function DataNode ($aa) {
        foreach ($aa as $k=>$v)
            $this->$k = $aa[$k];
    }
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
                $tdb[] = parseXML(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $tdb;
}

function parseXML($mvalues) {
    for ($i=0; $i < count($mvalues); $i++)
        $node[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    return new DataNode($node);
}

$db = readConfig("config.xml");
print_r($db);

?>
