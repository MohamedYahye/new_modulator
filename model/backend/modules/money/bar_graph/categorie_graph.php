<?php include('../login/api/database.php'); ?>
<?php include('../login/session.php'); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
<title>Budgetify</title>
   <link rel="stylesheet" type="text/css" href="css/nav_css.css">
   <link rel="stylesheet" type="text/css" href="../css/bar.css">
   <style>
   </style>
   <script type="text/javascript" src="nav/raphael.min.js"></script>
   <script type="text/javascript" src="nav/raphael.icons.min.js"></script>
   <script type="text/javascript" src="nav/wheelnav.min.js"></script>

</head>
<body>
<div id="container">
<div id="bars_box">
<div class="container vertical rounded">
<?php $categorie = $_GET['cat']; ?>
<h2><a id="go_back" class="return"><<</a>&nbsp;&nbsp;&nbsp;Jaarkosten van <?php echo $categorie ?></h2>

  <!-- Horizontal, rounded -->
  <?php

  //echo $categorie;
  function get_items($cat, $user_id, $month){
      global $db;
      $query = $db->prepare('SELECT categorie.cat_naam , overzicht.maand, overzicht.totaal_bedrag
        FROM overzicht INNER JOIN categorie ON overzicht.categorie_id=categorie.id 
        WHERE MONTH(overzicht.maand) = '.$month.' AND YEAR(overzicht.maand) =  YEAR(CURRENT_DATE) AND  overzicht.user_id="'.$user_id.'"AND categorie.cat_naam="'.$cat.'"');
      $query->execute();
      $out = $query->fetch(PDO::FETCH_ASSOC);
      // print_r($out);
      return array($out['cat_naam'] , $out['totaal_bedrag'], $out['maand']);
  }

  $months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
  for($i = 0; $i < 12; $i ++){

    $datum = get_items($categorie, $user_id,  $i+1)[2];
    if(empty(get_items($categorie, $user_id,  $i+1)[1])){
      $number = 0;
    } else {
      $number =  get_items($categorie, $user_id,  $i+1)[1];;
    }

    ?>
      <div class="progress-bar">
      <span>€<?php echo $number ?></span>
        <div class="progress-track">
          <div class="progress-fill">
          <span><?php echo $number ?></span>
          </div>
        </div>
        <div class="vertical_text">
          <span><?php echo $months[$i] ?></span>
        </div>
      </div>

    <?php
  }

  ?>
  </div>
</div>
<!-- Insert generated HTML5 code from here -> http://pmg.softwaretailoring.net -->
<div id='piemenu' data-wheelnav
 data-wheelnav-slicepath='DonutSlice'
 data-wheelnav-spreader data-wheelnav-spreaderpath='PieSpreader'
 data-wheelnav-marker data-wheelnav-markerpath='PieLineMarker'
 data-wheelnav-navangle='270'
 data-wheelnav-titleheight='45'
 data-wheelnav-cssmode 
 data-wheelnav-init>
 <div data-wheelnav-navitemicon='<?php echo $categorie ?>' onmouseup='location.href = "bar_graph/index.php"'></div>
 <div data-wheelnav-navitemicon='<?php echo date('F') ?>' onmouseup='location.href = "index.php"'></div>
 <div data-wheelnav-navitemicon='Home' onmouseup='location.href = "../home.php"'></div>
 <div data-wheelnav-navitemicon='Toevoegen' onmouseup='location.href = "../financieel_overzicht/index.php"'></div>
 <div data-wheelnav-navitemicon='Categorieën' onmouseup='location.href = "../categorie/categorie.php"'></div>
 <div data-wheelnav-navitemicon='Uitloggen' onmouseup='location.href = "../login/logout.php"'></div>
</div> 
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
$("#go_back").on('click', function(e){
  e.preventDefault();
  location.href =  '../categorie/categorie.php';
});
window.onload = function () {
    //Insert generated JavaScript code from here -> http://pmg.softwaretailoring.net
    var piemenu = new wheelnav('piemenu');
    piemenu.spreaderInTitle = icon.plus;
    piemenu.spreaderOutTitle = icon.cross;
    piemenu.spreaderRadius = piemenu.wheelRadius * 0.34;
    piemenu.sliceInitPathFunction = piemenu.slicePathFunction;
    piemenu.initPercent = 0.1;
    piemenu.wheelRadius = piemenu.wheelRadius * 0.83;
    piemenu.createWheel();
};



var array = [];
var sum, percent, pTop;
$('.vertical .progress-fill span').each(function(){
  var bedrag = $(this).html();
  bedrag = parseFloat(bedrag);

  array.push(bedrag);

  //total
  sum = array.reduce(add, 0);

});
$('.vertical .progress-fill span').each(function(e){
  percent = (array[e] / sum) * 100;
  percent = percent.toFixed(0);

  pTop = 100 - percent + "%";
  
  $(this).html(percent + '%');

  $(this).parent().css({
    'height' : percent + "%",
    'top' : pTop
  });
});
function add(total, number) {
    return total + number;
    }
</script>