<!DOCTYPE html>
<?php require_once('session.php');?>
<html lang="mk">
<head>
<meta  http-equiv="Content-Type" content="text/html; charset=UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
</head>
<body>

<?php

function finalSolution( $username ) {
  $query = "SELECT PrijavaZaIspit.ID, Predmet.ImeNaPredmet,Predmet.KodNaPredmet,Predmet.Semestar, Predmet.Krediti,AktiviranPredmet.UcebnaGodina, Profesor.Ime ||' '|| Profesor.Prezime AS 'Profesor'
FROM PrijavaZaIspit JOIN 
ListaNaStudentiNaAktiviranPredmet ON PrijavaZaIspit.IdStudentiNaPredmet = ListaNaStudentiNaAktiviranPredmet.IdStudentiNaPredmet,
AktiviranPredmet ON ListaNaStudentiNaAktiviranPredmet.AP_ID = AktiviranPredmet.AP_ID,
Student ON ListaNaStudentiNaAktiviranPredmet.BrojNaIndex = Student.BrojNaIndex,
Predmet ON AktiviranPredmet.KodNaPredmet = Predmet.KodNaPredmet,
Profesor ON AktiviranPredmet.IDNaVaraboten = Profesor.IDNaVraboten
WHERE Student.BrojNaIndex = :indeks";
  $conn = new PDO( 'sqlite:db/db_member.sqlite3' );
  $stmt = $conn->prepare( $query );
  $stmt->bindParam( ':indeks', $username );
  $stmt->execute();
  $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
  return($rows);
}

function getInfo($username){
  $query = "SELECT
  Student.BrojNaIndex,
  Student.Ime,
  Student.Prezime,
  Student.StatusNaStudent,
  Nasoka.StudentskaPrograma,
  Student.Nasoka
  FROM Student JOIN 
  Nasoka On Student.Nasoka = Nasoka.Nasoka
  WHERE Student.BrojNaIndex= :indeks";
  $conn = new PDO( 'sqlite:db/db_member.sqlite3' );
  $stmt = $conn->prepare( $query );
  $stmt->bindParam( ':indeks', $username );
  $stmt->execute();
  $sInfo = $stmt->fetch(PDO::FETCH_ASSOC);
  return $sInfo;
  console_log($sInfo);
}
/*
function writeToPredmeti($rows){
  //this 3 lines of code write the statement into student.json file or createsit if it doesn't exist
  $fp = fopen( 'dozvoleniPredmeti.json', 'w' );
  fwrite( $fp, json_encode( $rows, JSON_UNESCAPED_UNICODE ) );
  fclose( $fp );
}
*/
function console_log( $output, $with_script_tags = true ) {
  $js_code = 'console.log(' . json_encode( $output, JSON_HEX_TAG ) .
  ');';
  if ( $with_script_tags ) {
    $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}

function populateSelect($username){
  $query = "SELECT Predmet.ImeNaPredmet, AktiviranPredmet.AP_ID
  FROM ListaNaStudentiNaAktiviranPredmet 
  JOIN
  Predmet ON AktiviranPredmet.KodNaPredmet = Predmet.KodNaPredmet,
  Student ON ListaNaStudentiNaAktiviranPredmet.BrojNaIndex = Student.BrojNaIndex,
  AktiviranPredmet ON ListaNaStudentiNaAktiviranPredmet.AP_ID = AktiviranPredmet.AP_ID
  WHERE AktiviranPredmet.AP_ID NOT IN (
  SELECT AktiviranPredmet.AP_ID
  FROM PrijavaZaIspit 
  JOIN
  Predmet ON AktiviranPredmet.KodNaPredmet = Predmet.KodNaPredmet,
  Student ON ListaNaStudentiNaAktiviranPredmet.BrojNaIndex = Student.BrojNaIndex,
  AktiviranPredmet ON ListaNaStudentiNaAktiviranPredmet.AP_ID= AktiviranPredmet.AP_ID,
  ListaNaStudentiNaAktiviranPredmet ON PrijavaZaIspit.IdStudentiNaPredmet = ListaNaStudentiNaAktiviranPredmet.IdStudentiNaPredmet
  WHERE Student.BrojNaIndex = :indeks
  )AND Student.BrojNaIndex = :indeks";
  $conn = new PDO( 'sqlite:db/db_member.sqlite3' );
  $stmt = $conn->prepare( $query );
  $stmt->bindParam( ':indeks', $username );
  $stmt->execute();
  $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
  //We no longer create a json file to comunicate with the JS script
  //writeToPredmeti($rows);
  return($rows);

}

$result = finalSolution( $_SESSION[ 'username' ] );
$sInfo = getInfo($_SESSION[ 'username' ] );
$dozvoleniPredmeti = populateSelect($_SESSION[ 'username' ]);
// we use this cookie to inform the subjects available for the student to apply for an exam to the js script
setcookie('predmeti',json_encode($dozvoleniPredmeti,JSON_UNESCAPED_UNICODE));
?>


<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="navbar navbar-left"> <a class="navbar-brand" href="https://msu.edu.mk/">МСУ</a> </div>
  <div class="navbar-nav ml-md-auto"> <a class="navbar-brand">Управување со Предмети</a> </div>
  <div class="navbar-nav ml-auto">
		<div> <p class="navbar-brand"> <?php echo $sInfo['Ime'].' '.$sInfo['Prezime'];?></div>
		<div> <a class="navbar-brand" href="logout.php"> Одјави се </a></div>
  </div>
</nav>

<!-- dinamicki se vnesuvat podatoci za student -->
<div class="container-fluid">
<div class="col-2 col-md-2" style="margin-top: 5px" >
  <div class="table-responsive">
  <table class="table-striped"> 
  <?php 
  echo "<tr><th>Број на Индекс</th><td align =\"center\">#".$sInfo['BrojNaIndex']."</td></tr>";
  echo "<tr><th>Име</th><td align =\"center\">".$sInfo['Ime']."</td></tr>";
  echo "<tr><th>Презиме</th><td align =\"center\">".$sInfo['Prezime']."</td></tr>";
  echo "<tr><th>Статус</th><td align =\"center\">".$sInfo['StatusNaStudent']."</td></tr>";
  echo "<tr><th>Студентска Програма</th><td align =\"center\">".$sInfo['StudentskaPrograma']."</td></tr>";
  echo "<tr><th valign=\"middle\">Насока</th><td align =\"center\">".$sInfo['Nasoka']."</td></tr>";
  ?>
  </table>
  </div>
</div>
<div class="table-responsive" id="Tpredmet">
  <table class="table-striped col-md-9">
  <?php
  $brojNaPrijavi =count($result);
  echo "<tr>";
  echo "<th>Број на пријава</th> <th>Име на Предмет</th> <th>Код на Предмет</th> <th>Семестар</th> <th>Кредити</th> <th>Учебна Година</th> <th>Професор</th>";
  echo "</tr>";
  for($i=0; $i<$brojNaPrijavi;$i++){
  echo "<tr><td>".$result[$i]['ID']."</td><td>".$result[$i]['ImeNaPredmet']."</td><td>". $result[$i]['KodNaPredmet']."</td><td>".$result[$i]['Semestar']."</td><td>".$result[$i]['Krediti']."</td><td>".$result[$i]['UcebnaGodina']."</td><td>".$result[$i]['Profesor']."</td></tr>";
  }
    
 
  ?>
  </table>
  <div id="selMenu"  style="padding-top: 10px;">
  <button type="button" class="btn btn-secondary" id="btn">+</button>
  </div>
  </div>
</div>
<script src="js/main.js"></script>
</body>
</html>