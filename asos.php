<?php

$file = 'cx_asos.xml.1';
$wrapperName = 'sku';

$feed = new SimpleXMLElement(file_get_contents($file));

$colors = array();

foreach ($feed->products->product as $product) {
	$url = $product->url;
	foreach ($product->skus->sku as $sku) {
		if ($sku['color'] == 'No Colour') {
			$col = (string)$sku['original_color'];
			$colors[$col] = $url;
		}
	}
}

echo count($colors);
echo '<br/>';
echo '<br/>';

foreach ($colors as $color => $url) {
	echo '<a href="'.$url.'" target="_blank">'.$color.'</a><br/>';
}