<?php include('../login/api/database.php'); ?>
<?php include('../login/session.php'); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
<title>Budgetify</title>
   <link rel="stylesheet" type="text/css" href="css/nav_css.css">
   <link rel="stylesheet" type="text/css" href="../css/bar.css">
   <script type="text/javascript" src="nav/raphael.min.js"></script>
   <script type="text/javascript" src="nav/raphael.icons.min.js"></script>
   <script type="text/javascript" src="nav/wheelnav.min.js"></script>

</head>
<body>
<div id="container">
<div id="bars_box">
<div class="container vertical rounded">
<!-- <h3 id="jaar_overzicht" class="jaar">Bekijk hele jaar</h3>
 -->
  <!-- Horizontal, rounded -->
  <?php
  error_reporting(0);

  $type = $_SESSION['type'];

  if($type == 'maand'){
    ?><h2>Uitgaven <?php echo date('F'); ?></h2>
    <h3 id="jaar_overzicht" class="jaar">Bekijk hele jaar</h3><?php
  } else if($type = 'jaar'){
    ?><h2>Uitgaven <?php echo date('Y'); ?></h2>
    <h3 id="jaar_overzicht" class="maand">Bekijk huidige maand</h3><?php
  }

  function get_items($name, $user_id, $type){
      global $db;
      if($type == 'maand') {
        $query = $db->prepare('SELECT categorie.cat_naam, overzicht.maand, overzicht.totaal_bedrag , overzicht.user_id 
          FROM overzicht INNER JOIN categorie ON overzicht.categorie_id=categorie.id 
          WHERE MONTH(overzicht.maand) = MONTH(CURRENT_DATE) AND YEAR(overzicht.maand) = YEAR(CURRENT_DATE) AND overzicht.user_id="'.$user_id.'"AND categorie.cat_naam="'.$name.'"');
        $query->execute();
        $out = $query->fetch(PDO::FETCH_ASSOC);
        return array($out['cat_naam'], $out['totaal_bedrag'], $out['maand']);
      } else {
        $query = $db->prepare('SELECT categorie.cat_naam, overzicht.maand, overzicht.totaal_bedrag , overzicht.user_id 
          FROM overzicht INNER JOIN categorie ON overzicht.categorie_id=categorie.id 
          WHERE YEAR(overzicht.maand) = YEAR(CURRENT_DATE) AND  overzicht.user_id="'.$user_id.'"AND categorie.cat_naam="'.$name.'"');
        $query->execute();
        $out = $query->fetchAll(PDO::FETCH_ASSOC);
        $count = count($out);

        $nieuw_totaal = 0;
        if($count > 1){
          for($i = 0; $i < $count; $i++){
            $nieuw_totaal = $nieuw_totaal + $out[$i]['totaal_bedrag'];
          }
        } else {
          if($out){
            $nieuw_totaal = $out[0]['totaal_bedrag'];
          }
        }
        if($out){
          return array($out[0]['cat_naam'], $nieuw_totaal, $out[0]['maand']);
        }
      }
  }


  $cat_name_array = ['Woonlasten', 'Telefoon', 'Internet/TV', 'Verzekeringen','School','Abonnementen','Boodschappen','Kleding','Uitgaan','Vrije_tijd', 'Overige'];
  for($i = 0; $i < count($cat_name_array); $i++){
    $array_name = $cat_name_array[$i];
    $empty_number;
    $empty_name;
    $datum = get_items($array_name, $user_id, $type)[2];

    if(empty(get_items($array_name, $user_id, $type)[0])){
      $empty_number = 0;
      $empty_name = $cat_name_array[$i];
    } else {
      $empty_name = get_items($array_name, $user_id, $type)[0];
      $empty_number =  get_items($array_name, $user_id, $type)[1];
    }
    ?>

        <div class="progress-bar">
        <span>€<?php echo $empty_number ?></span>
          <div class="progress-track">
            <div class="progress-fill">
              <span><?php  echo $empty_number; ?></span>
            </div>
          </div>
          <div class="vertical_text">
            <span><?php echo $empty_name ?></span>
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
    <div data-wheelnav-navitemicon='<?php echo date('F') ?>' onmouseup='location.href = "bar_graph/index.php"'></div>
    <div data-wheelnav-navitemicon='Toevoegen' onmouseup='location.href = "../financieel_overzicht/index.php"'></div>
    <div data-wheelnav-navitemicon='Categorieën' onmouseup='location.href = "../categorie/categorie.php"'></div>
    <div data-wheelnav-navitemicon='Uitloggen' onmouseup='location.href = "../login/logout.php"'></div>
    <div data-wheelnav-navitemicon='Home' onmouseup='location.href = "../home.php"'></div>
</div> 

<div id="stuff">
<?php 
/*
$query = $db->prepare('SELECT uitgaven.naam, uitgaven.bedrag, uitgaven.datum, categorie.cat_naam FROM uitgaven INNER JOIN categorie ON uitgaven.categorie=categorie.id');
$query->execute();
$out = $query->fetch(PDO::FETCH_ASSOC);

print_r($out);
*/
?>

</div>


</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
$("#jaar_overzicht").on('click', function(){
  var curr_class = $(this).attr('class');

  var data = {
    type: curr_class
  }

  $.ajax({
      type: 'POST',
      url: 'get_type.php',
      data: data,
      dataType:'json', // add json datatype to get json
      success: function(data) {
          if(data.success) {
            location.reload();
          } else {
            //no succes
          }
      },
      error: function(err) {
          console.log(err);
      }
  });

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
for(var i = 0; i < array.length; i++){
  // console.log(pTop);
  // console.log(percent);
}
$('.vertical .progress-fill span').each(function(e){
  percent = (array[e] / sum) * 100;
  percent = percent.toFixed(0);
  //pTop = 100 - ( percent.slice(0, percent.length - 1) ) + "%";
  pTop = 100 - percent + "%";
  //console.log(pTop);
  $(this).html(percent + '%');
  // console.log(percent.toFixed(2));
  $(this).parent().css({
    'height' : percent + "%",
    'top' : pTop
  });
  if(percent < 5){
    $(this).css({
      'color' : 'black'
    });
  } 
});
function add(total, number) {
    return total + number;
    }
</script>