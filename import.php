     <?php
	 // here is the function which will help us to call the PHPExcel classe in our pages, you can put it in a file apart.
    $chemin = '\\afceCo.adroot.afceCo.bc.ca\DATA\SMI\System Wide Test\Automation\Data\Temp';
    set_include_path(get_include_path() . PATH_SEPARATOR . $chemin);//we specify the path" using linux"
    function __autoload($classe)
    {
    $fichier = str_replace
    (
    '_',
    DIRECTORY_SEPARATOR,
    $classe
    ) . '.php' ;
    require_once($fichier);
    }
    $fichierACharger = 'export.xlsx';//the worksheets to use
    $fichierType = PHPExcel_IOFactory::identify($fichierACharger);
    $objetALire = PHPExcel_IOFactory::createReader($fichierType);
    $objetALire->setReadDataOnly(true);
    $objPHPExcel = $objetALire->load($fichierACharger);
    //echo '<script>alert("le fichier a �t� charg� avec succes !");</script>';
    $feuille = $objPHPExcel->getSheet(0);//we specify the sheet to use
    $highestRow = $feuille->getHighestRow();//we select all the rows used in the sheet
    $highestCol = $feuille->getHighestColumn();// we select all the columns used in the sheet
    $indexCol = PHPExcel_Cell::columnIndexFromString($highestCol);we set an index on the columns
    //we show the data through a table
    echo'<table>'."\n";
    for($row = 9;$row <= $highestRow;$row++)
    {
    echo'<tr>'."\n";
    for($col = 1;$col <= $indexCol ;$col++)
    {
    $classeur = $feuille->getCellByColumnAndRow($col,$row)->getValue();
    echo'<td>'.$classeur.'</td>'."\n";
    }
    echo'</tr>'."\n";
    }
    echo'</table>'."\n";
    //connection to the database
    include("cnx_import_excel.php.inc");
    //importing files to the database
    for($row = 9;$row <= $highestRow;$row++)
    {
    $mat = $feuille->getCellByColumnAndRow(1,$row)->getValue();
    $noms = $feuille->getCellByColumnAndRow(2,$row)->getValue();
    $postPren = $feuille->getCellByColumnAndRow(3,$row)->getValue();
    $sexe = $feuille->getCellByColumnAndRow(4,$row)->getValue();
    //example of my data base
    $rqt = "INSERT INTO T_ETUDIANTS (E_ID,E_NOMS,E_PREPOST,E_SEXE)VALUES('$mat','$noms','$postPren','$sexe')";
    $accuse = mysql_query($rqt)or die (mysql_error());
    }
    //connection closing
    mysql_close();
	?>