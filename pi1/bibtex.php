<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<title>Untitled Document</title>
</head>

<body>
<?php
//http://luur.lub.lu.se/luurSru?version=1.1&operation=searchRetrieve&query=id%20exact%20%221434465%22%20OR%20id%20exact%20%221469119%22 
require_once("transtab_unicode_bibtex.php");
$i=0;
$xmlpath = "http://luur.lub.lu.se/luurSru?version=1.1&operation=searchRetrieve&query=id";
$id = $_GET["id"];

$idArray = explode(",", $id);

foreach($idArray as $key => $value) {
	if($i == 0) {
		$xmlpath .= "%20exact%20%22$value%22";
	} else {
		$xmlpath .= "%20OR%20id%20exact%20%22$value%22";
	}
	$i++;
}		

//$xmlpath .= "&sortKeys=$sorting,,$sortorder";
//echo $xmlpath;

$dom = new domDocument;
$dom->preserveWhiteSpace = false;
$dom->load($xmlpath);

$artists = $dom->documentElement;
$results = $dom->getElementsByTagName('records')->item(0);

foreach ($artists->childNodes as $artist) {

	$records = $artist->getElementsByTagName('record');

	foreach ($records as $record) {

		$conference = "";
		$genre = "";
		$journal = "";
		$location = "";
		$title = "";
		$url = "";
		$journal_url = "";
		$defenseDate = "";
		$year = "";
		$disputation = "";
		$placeTerm = "";
		
		$recordidentifiers = $record->getElementsByTagName('recordIdentifier');
		$recordidentifier = $recordidentifiers->item(0)->firstChild->nodeValue;

		$titles = $record->getElementsByTagName('title');
		$title = $titles->item(0)->firstChild->nodeValue;
		$bibTitle = $title;
		
		$names = $record->getElementsByTagName('name');
		$author="";
		foreach ($names as $name) {
			
			$nameparts = $name->getElementsByTagName('namePart');
			$namepart = $nameparts->item(0)->firstChild->nodeValue;
			$namepart .= ' ' . $nameparts->item(1)->firstChild->nodeValue;
			$namepart .= ' ' . $nameparts->item(2)->firstChild->nodeValue;
			
			$roleterms = $name->getElementsByTagName('roleTerm');
			$roleterm = $roleterms->item(0)->firstChild->nodeValue;
			
			$affiliations = $name->getElementsByTagName('affiliation');
			$affiliation = $affiliations->item(0)->firstChild->nodeValue;
			
			//Författare
			if($roleterm == "author") {
				if($author) $author = trim($author) . ", ";
				if($affiliation == "") {
					$author .= trim($namepart);
				} else {
					$author .= "<a href=\"#\" onClick=\"lista('$scope', 'listAuthor', '$affiliation,$namepart', '1', '$lang', '$sorting', '$sortorder', '$maximum_records', '', ''); return false;\">" . trim($namepart) . "</a>";
				}
				if($bibAuthor) $bibAuthor .= " and ";
				$bibAuthor .= trim($namepart);
			}
			
				//Ev konferens
			if($name->getAttribute('type')=="conference") {
				$conference = $namepart;
			}
		}
		
			//Ev plats för konferens
		$placeTerms = $record->getElementsByTagName('placeTerm');
		$placeTerm = $placeTerms->item(0)->firstChild->nodeValue;
		
			//Dokumenttyp
		$genres = $record->getElementsByTagName('genre');
		$genre = $genres->item(0)->firstChild->nodeValue;
		
			//dateissued
		$dateissueds = $record->getElementsByTagName("dateIssued");
		$dateissued = $dateissueds->item(0)->firstChild->nodeValue;
		
			//Publisher
		$publishers = $record->getElementsByTagName("publisher");
		$publisher = $publishers->item(0)->firstChild->nodeValue;
		
			//Ev tidskrift
		if($genre == "article") {
			$relatedItems = $record->getElementsByTagName('relatedItem');
			foreach ($relatedItems as $relatedItem) {
				if($identifier) $identifier .= ", ";
				if($relatedItem->getAttribute('type')=="host") {
					$journals = $relatedItem->getElementsByTagName('title');
					$journal = $journals->item(0)->firstChild->nodeValue;
					$bibJournal = $journal;
					$details = $relatedItem->getElementsByTagName('detail');
					foreach ($details as $detail) {
						if($detail->getAttribute('type')=="volume") {
							$journal .=  ", " . $langArray["volume"] . " " . $details->item(0)->firstChild->nodeValue;
							$bibVolume = $details->item(0)->firstChild->nodeValue;
						} elseif($detail->getAttribute('type')=="issue") {
							$journal .=  ", " . $langArray["issue"] . " " . $details->item(1)->firstChild->nodeValue;
							$bibIssue = $details->item(0)->firstChild->nodeValue;
						}
					}
					$starts = $relatedItem->getElementsByTagName('start');
					$start =  $starts->item(0)->firstChild->nodeValue;
					if($start) {
						$journal .=  ", " . $langArray["page"] . " " . $start;
						$bibStart = $start;
					}
					
					$ends = $relatedItem->getElementsByTagName('end');
					$end = $ends->item(0)->firstChild->nodeValue;
					if($end) {
						$journal .= ", " . $langArray["to"] . " " . $end;
						$bibEnd = $end;
					}
					
						//Ev länk till tidskrift
					$journal_urls = $relatedItem->getElementsByTagName('url');
					$journal_url = $journal_urls->item(0)->firstChild->nodeValue;
				}
			}
		}
		
		// series

		//Länk till fulltext
		$urls = $record->getElementsByTagName('url');
		$url = $urls->item(0)->firstChild->nodeValue;
		
		//Datum
		$years = $record->getElementsByTagName('dateIssued');
		$year = $years->item(0)->firstChild->nodeValue;
		
		$dateOthers = $record->getElementsByTagName('dateOther');
		$dateOther = $dateOthers->item(0)->firstChild->nodeValue;
		foreach($dateOthers as $x) {
			//if($x->getAttribute('type')=="defenseDate") $disputation = extractDate($defenseDate);
				//Ev datum för konferens
			if($x->getAttribute('type')=="conferenceDate") $conference .= ", " . $dateOther;
		} 

		//Bibtex
		$content = "<p>@inproceedings{LUP$recordidentifier,";
		$content .= "<br />&nbsp;&nbsp;author&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{" . $bibAuthor . "},";
		$content .= "<br />&nbsp;&nbsp;title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{{" . $bibTitle . "}},";
		if($journal) $content .= "<br />&nbsp;&nbsp;journal&nbsp;&nbsp;&nbsp;=&nbsp;{" . $bibJournal . "},";
		if($bibVolume) $content .= "<br />&nbsp;&nbsp;volume&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{" . $bibVolume . "},";
		if($publisher) $content .= "<br />&nbsp;&nbsp;publisher&nbsp;=&nbsp;{" . $publisher . "},";
		if($bibStart) $content .= "<br />&nbsp;&nbsp;pages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{" . $bibStart . "--" . $bibEnd . "},";
		$content .= "<br />&nbsp;&nbsp;year&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{" . $dateissued . "},";
		if($url) $content .=  "<br />&nbsp;&nbsp;url&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;{" . $url . "}";
		if(substr($content, -1, 1)==",") $content = substr($content, 0, -1);
		$content .= "<br />}";
		$content .= "</p>";
		//$content = utf8_decode($content);
		//echo strpos($content, "ö");
		foreach ($transtab_unicode_bibtex as $searchString => $replaceString) {
			//
			if (strpos($content, $searchString)) {
				//echo "$searchString;$replaceString<br />";
				$content = str_replace($searchString, $replaceString, $content);
			}
		}
		$content = "<pre>$content</pre>";
		echo $content;

	}
	
	
	
	
	
	
}

function ascii_convert($input) {
	$pattern = array(
	  '/å/',
	  '/Å/',
	  '/ä/',
	  '/Ä/',
	  '/ö/',
	  '/Ö/',
	  '/é/',
	);
	$replace = array(
	  '{\aa}',
	  '{\AA}',
	  '\"{a}',
	  '\"{A}',
	  '\"{o}',
	  '\"{O}',
	  '{\'e}',
	);
	$output = preg_replace($pattern, $replace, $input);
	return $output;
}

?>
</body>
</html>