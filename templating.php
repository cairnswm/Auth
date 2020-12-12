<?php

$template = "
			Hello {{customer_name}}
			
			Thank you for making the purchase of $ {{amount}}. 
			Your order shall be processed shortly.
			
            Regards
            {{from}}
			Glyndwr Covid Website";

# Your template tags + replacements
$row = array(
	'customer_xname' => 'Mike',
	'amount' => 400,
);

function fill_template($template, $row)
{
    $out = array(); $repl = array();
    preg_match_all('/{{[_a-zA-Z]*}}/', $template, $out);
    $replacements = array();
    foreach($out[0] as $item) {
        
        $repl[] = $item;
        $item = str_replace(["{","}"], ["",""], $item);
        if (isset($row[$item])) {
        $replacements[] = $row[$item];
        } else {
            $replacements[] = "";
        }
    }
	$final = str_replace($repl, $replacements , $template);
	
	return $final;
}

echo fill_template($template, $row);