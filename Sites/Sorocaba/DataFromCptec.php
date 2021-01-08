<?php

  //Ce script permet de récupérer les données météo de sorocaba du site web "cptec"


  require "db.php";//call the database

  //recuperation du site web
  $adresse = "https://www.cptec.inpe.br/sp/sorocaba";
  $datas = file_get_contents($adresse);

  //on decoupe le code du site
  $transfo = explode("<!-- BLOCO PREVISÃO -->",$datas);//on ne prend pas l'header
  $transfo2 = explode("<div class=\"tab-pane\" id=\"meteograma\" role=\"tabpanel\">",$transfo[1]);//on enleve les graphes</div>
  $transfo3 = explode("\n",$transfo2[0]);//on decoupe les donneées par ligne

  //ATTENTION SI LE SITE CHANGE CE PROGRAMME NE MARCHERA PLUS
  $i = 0;
  $j = 0;
  $tmp = 0;
  $datarray = array();

  //on crée un tableau qui ne prend que les valeur importante
  while ($i < sizeof($transfo3)) {

    //on en prend que les <span> <label> <small> <img>
    if(strpos($transfo3[$i],"span") != false || strpos($transfo3[$i],"small") != false || strpos($transfo3[$i],"label") != false || strpos($transfo3[$i],"img") != false){
      $datarray[] = $transfo3[$i];
    }
    $i++;
  }

  //on tri les valeurs importante
  while ($j < sizeof($datarray)) {
    //on check si il y a le matin
    if(strpos($datarray[$j],"Manh&atilde;") != false){
      $tmp++;
    }elseif (strpos($datarray[$j],"Tarde") != false) {
      $tmp++;//variable to know if they are the morning and the afternoon
    }
    $j++;
  }


  $dateT = date("d/m/Y");//date au format d/m/Y

  $Tmax = (int) filter_var($datarray[4+(3*$tmp)], FILTER_SANITIZE_NUMBER_INT); //on ne prend que la valeur entiere
  $Tmin = (int) filter_var($datarray[5+(3*$tmp)], FILTER_SANITIZE_NUMBER_INT);
  $Hum = (int) filter_var($datarray[6+(3*$tmp)], FILTER_SANITIZE_NUMBER_INT);
  $UV = (int) filter_var($datarray[7+(3*$tmp)], FILTER_SANITIZE_NUMBER_INT);

  if($tmp == 2)//tmp = 2 matin + aprem + nuit, tmp = 1 aprem + nuit , tmp = 0 nuit
  {
    //date - imgMatin - imgAprem -imgSoir - temp max - temp min - humidity - UV
    $today = array($dateT,$datarray[3],$datarray[6],$datarray[9],$Tmax,$Tmin,$Hum,$UV);
  }elseif ($tmp == 1) {
    $today = array($dateT,$datarray[3],$datarray[6],$Tmax,$Tmin,$Hum,$UV);
  }else {
    $today = array($dateT,$datarray[3],$Tmax,$Tmin,$Hum,$UV);
  }


  $transfo4 = explode("Previs&atilde;o Completa</button></span>",$transfo2[0]);//on enleve les graphes</div>
  $transfo5 = explode("\n",$transfo4[1]);//on decoupe les donneées par ligne


  //date - img - tmpmax - tmpmin - humidity - UV

  //j'utilise explode pour pouvoir separer des données entre 2 balises
  for ($i=0; $i <5 ; $i++) {

  $datePp = explode("</span>",$transfo5[5+(21*$i)]);
  $datePp = explode("<span class=\"top5\">",$datePp[0]);
  $dateP = implode($datePp);


  $imgP = $transfo5[10+(21*$i)];

  $TmaxPp = explode("&deg;</span>",$transfo5[7+(21*$i)]);
  $TmaxPp = explode("<span class=\"temp-min \">",$TmaxPp[0]);
  $TmaxP = implode($TmaxPp);

  $TminPp = explode("&deg;</span>",$transfo5[8+(21*$i)]);
  $TminPp = explode("<span class=\"temp-max \">",$TminPp[0]);
  $TminP = implode($TminPp);

  $HumPp = explode("%</span>",$transfo5[14+(21*$i)]);
  $HumPp = explode("<span class=\"text-center\">",$HumPp[0]);
  $HumP = implode($HumPp);

  $UVPp = explode("</span>",$transfo5[18+(21*$i)]);
  $UVPp = explode("<span class=\"ml-3 text-center\">",$UVPp[0]);
  $UVP = implode($UVPp);

  $prevision = array($dateP,$imgP,$TmaxP,$TminP,$HumP,$UVP);
  $previsionArray[$i] = $prevision;


  // -----------------------   import to prevision database -----------------------//

  $dateToData = $dateP."/".date("Y");
  $dateToData = str_replace("/","-",$dateToData);
  $dateToData = date("Y-m-d", strtotime($dateToData));

  //check duplication
  $duplication = 0;
  $checkTimestamp = "SELECT Day_Date FROM prevision ORDER BY ID_prevision desc";
  $query = mysqli_query($conn, $checkTimestamp) or die (mysqli_error($conn));
  while ($row = $query->fetch_assoc())
  {
    if($row["Day_Date"] == $dateToData)
    {
      $duplication = 1;//duplication trouvé
    }

  }

  if($duplication == 0){
    $req = "INSERT INTO prevision VALUES (NULL, '$dateToData', '$TmaxP', '$TminP', '$HumP','$UVP', 0)";
    $res = mysqli_query($conn,$req);
  }else {
    $duplication = 0;
  }

}

  $prevision1 = $previsionArray[0];
  $prevision2 = $previsionArray[1];
  $prevision3 = $previsionArray[2];
  $prevision4 = $previsionArray[3];
  $prevision5 = $previsionArray[4];

  // -----------------------   import to present database -----------------------//
  
  $dateToData = $today[0]."/".date("Y");
  $dateToData = str_replace("/","-",$dateToData);
  $dateToData = date("Y-m-d", strtotime($dateToData));
  
  $prevDateToData = $dateP."/".date("Y");
  $prevDateToData = str_replace("/","-",$prevDateToData);
  $prevDateToData = date("Y-m-d", strtotime($prevDateToData));
  
  //check duplication
  $duplication = 0;
  $checkTimestamp = "SELECT Day_Date FROM present ORDER BY Day_Date desc";
  $query = mysqli_query($conn, $checkTimestamp) or die (mysqli_error($conn));
  
  
  while ($row = $query->fetch_assoc())
  {
    if($row["Day_Date"] == $dateToData)
    {
      $duplication = 1;//duplication trouvé
    }

  }
  
  if($duplication == 0){
	
	if($tmp == 0)
	{
		$req = "INSERT INTO present VALUES ('$dateToData','$today[2]','$today[3]','$today[4]','$today[5]','0','$prevDateToData', '$TmaxP', '$TminP','$HumP', '$UVP','0')";
	}
	
	if($tmp == 1)
	{
		$req = "INSERT INTO present VALUES ('$dateToData','$today[3]','$today[4]','$today[5]','$today[6]','0','$prevDateToData', '$TmaxP', '$TminP','$HumP', '$UVP','0')";
	}
	
	if($tmp == 2)
	{
		$req = "INSERT INTO present VALUES ('$dateToData','$today[4]','$today[5]','$today[6]','$today[7]','0','$prevDateToData', '$TmaxP', '$TminP', '$HumP', '$UVP','0')";
	}
	
    $res = mysqli_query($conn,$req);
  }else {
    $duplication = 0;
  }

?>
