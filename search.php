<?php

    $content = '';
    $queryFilterString = '';
    $offset=null;
    $limit=null;
    $sorting=null;
    if($offset=='null' || $offset=='') $offset=0;
    if($limit=='null' || $limit=='') $limit=250;
    if($sorting=='null') $sorting = 'full_name desc';
    
    require __DIR__ . '/vendor/solr/Service.php';
 
    $solr = new Apache_Solr_Service( 'localhost', '8080', '/solr/lucat' );

    if ( ! $solr->ping() ) {
        $content = 'Solr service not responding.';
        exit;
    }

    $scope = '011014004';
    $queries = array(
        "group_lucat_id:$scope",
    );
    
    $p = array(
        'sort' => $sorting
    );
    
    foreach ( $queries as $query ) {
        $response = $solr->search( $query, $offset, $limit, $p );

        $numberOfHits = $response->response->numFound;
        $content .= "$numberOfHits<ul>";
        if ( $response->getHttpStatus() == 200 ) { 

            if ( $response->response->numFound > 0 ) {
        /*print '<pre>';
        print json_encode($response);
        print '</pre>';
                foreach ( $response->response->docs as $doc ) { 
                    $content .= "<li>$doc->full_name</li>";
                    
                }*/
                $results = array();
foreach($response->response->docs as $document) {
    $item = array();
    foreach($document as $field => $value) {
        if($field != '_version_') $item[$field] = $value;
    }
                $item['staff_category'] = $catvalue;

    $results[] = $item;
}

print '<pre>';
print_r($results);
print '</pre>';
die();
//$solr->deleteByQuery($query);
$documents = array();
  
foreach ( $results as $item => $fields ) {

    $part = new Apache_Solr_Document();

    foreach ( $fields as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $data ) {
               $part->setMultiValue( $key, $data );
            }
        }
        else {
            $part->$key = $value;
        }
    }

    $documents[] = $part;
}


/*try {
    $solr->addDocument( $part );
    $solr->commit();
    $solr->optimize();
     echo 'getFeUsers done!';
}
catch ( Exception $e ) {
    echo $e->getMessage();
}*/

            }
        }
        else {
          $content = '<li>' . $response->getHttpStatusMessage() . '</li>';
        }
    }
    
    echo "<ul>$content</ul>";
  

/*print '<pre>';
print_r($response);
print '</pre>';*/
?>

