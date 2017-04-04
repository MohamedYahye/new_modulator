<?php include('login/api/database.php'); ?>
<?php
session_start();
error_reporting(0);

$user_id =  $_SESSION['id'];
$type = $_SESSION['type'];
//if the session is not set (user is not logged in) go back to the login form.
if(!isset($_SESSION['id'])) {
  header('Location: login/loginForm.php');
  die();
}

?>
<!DOCTYPE html>
<html>
<head>
   <link rel="shortcut icon" type="image/x-icon" href="euro.gif">
   <title>Budgetify</title>
   <link rel="stylesheet" type="text/css" href="css/home.css">
   <link rel="stylesheet" type="text/css" href="bar_graph/css/nav_css.css">
   <script type="text/javascript" src="bar_graph/nav/raphael.min.js"></script>
   <script type="text/javascript" src="bar_graph/nav/raphael.icons.min.js"></script>
   <script type="text/javascript" src="bar_graph/nav/wheelnav.min.js"></script>

   <script src="sweetalert/sweetalert2.min.js"></script>
   <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">

</head>
<?php
$query = $db->prepare('SELECT username, budget FROM login WHERE id = "'.$_SESSION['id'].'"');
$query->execute();
$naam = $query->fetch(PDO::FETCH_ASSOC);

?>
<div id="container">
  <div id="message_box">
    <div id="name_text">Hallo <?php echo $naam['username']; ?></div>
    <div id="log_text">Bent u niet <?php echo $naam['username']; ?> ? <a href="login/logout.php">Log hier uit</a></div>
    <div id="text">Welkom <?php echo $naam['username'] ?> bij uw eigen persoonlijk financieel overzicht. <br />
    Hier kunt u <a href="financieel_overzicht/index.php">uitgaven of inkomsten toevoegen</a>.
    U krijgt een overzicht te zien bij <a href="categorie/categorie.php">categorieën</a> over hoeveel uw uitgaven zijn in die specifieke categorie in een bepaalde tijd van het jaar.
    Ook kunt u over het <a href='bar_graph/index.php'">hele jaar of de huidige maand</a> heen zien hoeveel u bij elke categorie heeft uitgegeven.



</div>

<?php 
if(!$naam['budget']){
  ?>
  <script>
  var user_id = <?php echo json_encode($user_id); ?>;
  </script>
  <script>
  swal({
    title: 'Vul uw saldo in.',
    input: 'number',
    showCancelButton: false,
    confirmButtonText: 'Submit',
    showLoaderOnConfirm: true,
    preConfirm: function(number) {
      return new Promise(function(resolve, reject) {
        setTimeout(function() {
          if (number === '') {
            reject('Uw saldo kan niet leeg zijn.');
          } else {
            resolve();
          }
        }, 2000);
      });
    },
    allowOutsideClick: false
  }).then(function(number) {
 $.ajax({
     type: 'POST',
     url: 'api/insert_budget.php',
     data: {budget:number, user_id:user_id},
     dataType: 'json',
     success: function(data) {
         if(data.success) {
          swal({
            type: 'success',
            title: 'Uw saldo is succesvol toegevoegd.',
            html: 'toegevoegd saldo:€ ' + number
          });
           // document.getElementById("insert_uitgave").reset();
             
             // window.location.href = "loginForm.php";
         } else {
             //no succes
         }
     },
     error: function(err) {
         console.log(err);
     }
 });
  })  </script>
  <?php
} else {

}

?>

  </div>
  <div id='piemenu' data-wheelnav
   data-wheelnav-slicepath='DonutSlice'
   data-wheelnav-spreader data-wheelnav-spreaderpath='PieSpreader'
   data-wheelnav-marker data-wheelnav-markerpath='PieLineMarker'
   data-wheelnav-navangle='270'
   data-wheelnav-titleheight='45'
   data-wheelnav-cssmode 
   data-wheelnav-init>
    <div data-wheelnav-navitemicon='Home'></div>
    <div data-wheelnav-navitemicon='<?php echo date('F') ?>' onmouseup='location.href = "bar_graph/index.php"'></div>
    <div data-wheelnav-navitemicon='Toevoegen' onmouseup='location.href = "financieel_overzicht/index.php"'></div>
    <div data-wheelnav-navitemicon='Categorieën' onmouseup='location.href = "categorie/categorie.php"'></div>
    <div data-wheelnav-navitemicon='Uitloggen' onmouseup='location.href = "login/logout.php"'></div>
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



</script>