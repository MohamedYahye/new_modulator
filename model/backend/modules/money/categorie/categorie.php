<?php include('../login/api/database.php'); ?>
<?php include('../login/session.php'); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/x-icon" href="../euro.gif">
<title>Budgetify</title>
   <link rel="stylesheet" type="text/css" href="../css/home.css">
   <link rel="stylesheet" type="text/css" href="../bar_graph/css/nav_css.css">
   <script type="text/javascript" src="../bar_graph/nav/raphael.min.js"></script>
   <script type="text/javascript" src="../bar_graph/nav/raphael.icons.min.js"></script>
   <script type="text/javascript" src="../bar_graph/nav/wheelnav.min.js"></script>

</head>
<?php
$query = $db->prepare('SELECT username FROM login WHERE id = "'.$_SESSION['id'].'"');
$query->execute();
$naam = $query->fetch(PDO::FETCH_ASSOC);

$lijst_query = $db->prepare('SELECT * FROM categorie ORDER BY cat_naam');
$lijst_query->execute();
$list = $lijst_query->fetchAll(PDO::FETCH_ASSOC);



// $lijst_query = $db->prepare('SELECT categorie.cat_naam, overzicht.totaal_bedrag FROM overzicht 
//   INNER JOIN categorie ON overzicht.categorie_id=categorie.id 
//           WHERE MONTH(overzicht.maand) = MONTH(CURRENT_DATE) AND YEAR(overzicht.maand) =  YEAR(CURRENT_DATE) 
//   AND overzicht.user_id = "'.$_SESSION['id'].'"');
// $lijst_query->execute();
// $list = $lijst_query->fetchAll(PDO::FETCH_ASSOC);
//<td><?php echo $list_item['totaal_bedrag']; ?/></td>
//        <th>Bedrag maand <?php echo date('F') ?/></th>

?>
<div id="container">
  <div id="message_box">
    <div id="name_text" style="width:90%;">Uitgaven categorieën - <?php echo $naam['username'] ?> </div>
    <table style="width:90%;">
      <tr>
        <th>Categorie</th>
    	  <th>Jaaroverzicht <?php echo date('Y') ?></th>
      </tr>
      <?php 
      foreach($list as $list_item){
        ?>
          <tr class="cat_link">
            <td><?php echo $list_item['cat_naam']; ?></td>
            
            <td id="<?php echo $list_item['cat_naam']; ?>">>></td>
          </tr>
        <?php
      }
      ?>
    </table>
</div>
  <div id='piemenu' data-wheelnav
   data-wheelnav-slicepath='DonutSlice'
   data-wheelnav-spreader data-wheelnav-spreaderpath='PieSpreader'
   data-wheelnav-marker data-wheelnav-markerpath='PieLineMarker'
   data-wheelnav-navangle='270'
   data-wheelnav-titleheight='45'
   data-wheelnav-cssmode 
   data-wheelnav-init>
   <div data-wheelnav-navitemicon='Categorieën' onmouseup='location.href = "categorie/categorie.php"'></div>
   <div data-wheelnav-navitemicon='Uitloggen' onmouseup='location.href = "../login/logout.php"'></div>
   <div data-wheelnav-navitemicon='Home' onmouseup='location.href = "../home.php"'></div>
   <div data-wheelnav-navitemicon='<?php echo date('F') ?>' onmouseup='location.href = "../bar_graph/index.php"'></div>
   <div data-wheelnav-navitemicon='Toevoegen' onmouseup='location.href = "../financieel_overzicht/index.php"'></div>
  </div> 
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
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

$('.cat_link').on('click' , function(){
  var get_id = $(this).find('td')[1].id ;
	location.href="../bar_graph/categorie_graph.php?cat="+get_id+"";
})

</script>