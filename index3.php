<?php



function startElement($p, $element, $attributes){
    switch($element) {
        case 'DATASET':
            echo '<h1>Podaci zapisani u LV2.xml datoteci</h1><br>';
            break;
        case 'RECORD':
            echo '<div style="border: 1px solid #0000FF; padding: 5px">';
            break;
        case 'SLIKA':
            // echo '<h2>' . $element . ': </h2>';
            echo '<div><img src="';
            break;		
        case 'IME':
        case 'PREZIME':
        case 'EMAIL':
        case 'ZIVOTOPIS':
        case 'SPOL':
            echo '<h2>' . $element . ': </h2>';
            break;
        case 'ID':
            echo '<span style="opacity: 0">';
            break;
    }
}

function endElement($p, $element){
    switch($element) {
        case 'RECORD':
            echo '</div><br>';
            break;
        case 'SLIKA':
            echo'"/></div>';
            break;
        case 'IME':
        case 'PREZIME':
        case 'EMAIL':
        case 'ZIVOTOPIS':
            echo '<br>';
            break;
        case 'ID':
            echo '</span>';
    }
}

function handle_character_data($p, $cdata) {
    echo $cdata;
}










$p = xml_parser_create();
xml_set_element_handler($p, 'startElement', 'endElement');
xml_set_character_data_handler($p, 'handle_character_data');

$fp = @fopen('LV2.xml', 'r');
while ($data = fread($fp, 4096)) {
    xml_parse($p, $data, feof($fp));
}


xml_parser_free($p);


?>