<?php

function tree($elements, $parentId=0){
	$data = [];
	foreach($elements as $element){
		if($element->affiliate_parent == $parentId){
			$children = tree($elements, $element->ID);
			$element->children = $children ? $children : [];
			$data[] = $element;
		}
	}
	return $data;
}

function treeSelf($elements, $id=0){

	$data = [];
	foreach($elements as $element){
		if($element->ID == $id){
			$children = tree($elements, $element->ID);
			$element->children = $children ? $children : [];
			$data[] = $element;
		}
	}

	return $data;

}

function boyutOgrenme($item, $x=0){

	if(sizeof($item->children) > 0){
		$x++;
		boyutOgrenme($item->children, $x+1);
	}

	return $x;

}

function drawTree($items){

	echo "<ul>";
	foreach($items as $item){
		$size = count($item->children);
		echo "<li>".$item->ID.$item->adsoyad."</li>";

		if(count($item->children) > 0){

			drawTree($item->children);

		}


	}
	echo "</ul>";

}
