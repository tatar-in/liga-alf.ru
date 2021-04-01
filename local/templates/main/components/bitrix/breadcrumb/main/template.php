<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;
//echo '<pre>';print_r($arResult);echo '</pre>';
//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';



$strReturn .= '<nav aria-label="breadcrumb">';
$strReturn .= '		<ol class="breadcrumb px-0" style="background-color: #fff;">';
$strReturn .= '			<li class="breadcrumb-item">	
							<a href="/">Главная</a>	
						</li>';

$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
			<li class="breadcrumb-item">
				
				<a href="'.$arResult[$index]["LINK"].'">
					'.$title.'
				</a>

			</li>';
	}
	else
	{
		$strReturn .= '
			<li class="breadcrumb-item active" aria-current="page">
				'.$title.'
			</li>';
	}
}

$strReturn .= '
	</ol>
</nav>';

return $strReturn;
