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
   <style>
   #insert_uitgave {
    width:100%;
   }
   #insert_uitgave input {
    width:95%;
    padding:10px;
    margin-top: 2%;
   }
   #uitgave_cat {
    height: 40px;
    width: 100%;
    padding: 10px;
    font-size: 15px;
   }
   #versturen {
    width:100%!important;
   }
   #versturen:hover {
    border:2px solid #5882FA;
    border-radius: 5px;
   }
   .alert{
    font-size: 12px;
    color: #f00;
    display: none;
   }
   .wrong-entry {
    -webkit-animation-name: spaceboots;
    -webkit-animation-duration: 0.8s;
    -webkit-transform-origin:50% 50%;
    -webkit-animation-timing-function: linear;

    border-color: #ed1c24;
    color: #ed1c24;
   }
   @-webkit-keyframes spaceboots {
    0% { -webkit-transform: translate(2px, 1px) rotate(0deg); }
    10% { -webkit-transform: translate(-1px, -2px) rotate(-1deg); }
    20% { -webkit-transform: translate(-3px, 0px) rotate(1deg); }
    30% { -webkit-transform: translate(0px, 2px) rotate(0deg); }
    40% { -webkit-transform: translate(1px, -1px) rotate(1deg); }
    50% { -webkit-transform: translate(-1px, 2px) rotate(-1deg); }
    60% { -webkit-transform: translate(-3px, 1px) rotate(0deg); }
    70% { -webkit-transform: translate(2px, 1px) rotate(-1deg); }
    80% { -webkit-transform: translate(-1px, -1px) rotate(1deg); }
    90% { -webkit-transform: translate(2px, 2px) rotate(0deg); }
    100% { -webkit-transform: translate(1px, -2px) rotate(-1deg); }
   }
   #Inkomst {
    float: left;
   }
   #Uitgave {
    float: right;
   }
   #Inkomst, #Uitgave {
    width: 45%;
   }
   .pm {
    border: 1px solid black;
    padding: 5px;
   }
   .iou {
    width:100%;
   }
   </style>
</head>
<?php
$query = $db->prepare('SELECT budget FROM login WHERE id = "'.$_SESSION['id'].'"');
$query->execute();
$budget = $query->fetch(PDO::FETCH_ASSOC);

$uitgave_query = $db->prepare('SELECT uitgaven.id , uitgaven.naam ,uitgaven.bedrag, uitgaven.type, categorie.cat_naam, uitgaven.datum
  FROM uitgaven INNER JOIN categorie ON uitgaven.categorie=categorie.id 
  WHERE uitgaven.user_id="'.$user_id.'" ORDER BY id DESC LIMIT 10');
$uitgave_query->execute();
$uitgave_list = $uitgave_query->fetchAll(PDO::FETCH_ASSOC);

$cat_query = $db->prepare('SELECT * FROM categorie ORDER BY cat_naam');
$cat_query->execute();
$categorie_out = $cat_query->fetchAll(PDO::FETCH_ASSOC);

$budget_number = $budget['budget'];

if($budget_number >= 0){
  $class = 'green';
} else {
  $class = 'red';
}
?>
<div id="container">
  <div id="message_box">
    <div id="name_text" style="color:<?php echo $class ?>">Saldo:€ <?php echo $budget['budget']; ?></div>
    <br />
    <form id="insert_uitgave" autocomplete="off">
      <input type="text" name="naam" placeholder="Naam inkomst / uitgave" id="uitgave_naam"><br />
      <input type="number" name="bedrag" placeholder="bedrag" id="uitgave_bedrag"><br />
      <input type="date" name="datum" id="uitgave_datum"><br /><br />
    <select name="categorie" id="uitgave_cat" onchange="changeFunc();">
    <option value="" selected="true" disabled="true">Kies een categorie</option>
    <?php
    foreach($categorie_out as $categorie_option){
      $cat_option_id = $categorie_option['id'];
      $cat_option_name = $categorie_option['cat_naam']; ?>
      ?><option value="<?php echo $cat_option_id ?>"><?php echo $cat_option_name ?></option><?php
    }

    ?>
    </select><br /><br />
    <div id="iou">
      <div id="Inkomst" class="pm">Inkomst</div>
      <div id="Uitgave" class="pm">Uitgave</div>
    </div><br /><br />

    <div id="centerSpan">
    <span class="alert"></span>
    </div>
    <div id="uitgave_id" style="display:none;"></div>
    <div id="old_date" style="display:none;"></div>
    <div id="old_cat" style="display:none;"></div>
    <input id="versturen" class="send-btn" type="submit" value="Uitgave toevoegen">
    <input id="updaten" class="send-btn" type="submit" value="Update" style="display:none;">
    <input id="nieuwe_uitgave" class="send-btn" type="submit" value="Nieuwe uitgave" style="display:none;">

    </form>


    <table>
      <tr>
        <th>Uitgave naam</th>
        <th>Bedrag</th>
        <th>datum</th>
        <th>Categorie</th>
        <th>Type</th>
        <th style="display:none;"></th>
      </tr>
      <?php foreach($uitgave_list as $list_item){
        ?>
        <tr>
          <td><?php echo $list_item['naam']; ?></td>
          <td><?php echo $list_item['bedrag']; ?></td>
          <td><?php echo $list_item['datum']; ?></td>
          <td><?php echo $list_item['cat_naam']; ?></td>
          <td><?php echo $list_item['type']; ?></td>
          <td class="wijzig"><img id="wijzig_image" style="height:4%" src="../edit.jpg"></td>
          <td style="display:none;"><?php echo $list_item['id']; ?></td>
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
   <div data-wheelnav-navitemicon='Toevoegen' onmouseup='location.href = "../financieel_overzicht/index.php"'></div>
   <div data-wheelnav-navitemicon='Categorieën' onmouseup='location.href = "../categorie/categorie.php"'></div>
   <div data-wheelnav-navitemicon='Uitloggen' onmouseup='location.href = "../login/logout.php"'></div>
   <div data-wheelnav-navitemicon='Home' onmouseup='location.href = "../home.php"'></div>
   <div data-wheelnav-navitemicon='<?php echo date('F') ?>' onmouseup='location.href = "../bar_graph/index.php"'></div>
  </div> 
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
document.getElementById('uitgave_datum').valueAsDate = new Date();

var o_bedrag = 0;
var prev;
var new_id;
var o_bedrag;

$(".wijzig").on('click', function(){
  $("#versturen").hide();
  $("#versturen").prop('disabled', true);
  $("#versturen").hide();
  $("#updaten").show();
  $("#nieuwe_uitgave").show();
  var x = $(this).parent();
  $(x).each(function() {
      var naam = $(this).find("td").eq(0).html();
      o_bedrag = $(this).find("td").eq(1).html();
      var bedrag = $(this).find("td").eq(1).html();
      var datum = $(this).find("td").eq(2).html();
      var categorie = $(this).find("td").eq(3).html();
      var type = $(this).find("td").eq(4).html();
      var uitgave_id = $(this).find("td").eq(6).html();
      
      o_bedrag = bedrag;
      $("#uitgave_id").val(uitgave_id);

      $("#uitgave_naam").val(naam);
      $("#uitgave_bedrag").val(bedrag);
      $("#uitgave_datum").val(datum);
      $("#old_date").val(datum);

      var previous;

      $("select").on('focus', function () {
          // Store the current value on focus and on change
          previous = this.value;
      }).change(function() {
          // Do something with the previous value after the change
          prev = previous;
          // Make sure the previous value is updated
          previous = this.value;
          new_id = this.value;
      });


      $('#'+type).click();

      $("#uitgave_cat option").filter(function() {
          return $(this).text() == categorie; 
      }).prop('selected', true);
  });
});
$('#nieuwe_uitgave').on('click', function(e) {
  location.reload();
});
$('#updaten').on('click', function(e) {
  e.preventDefault();
  var naam = $('#uitgave_naam').val();
  var bedrag = $('#uitgave_bedrag').val();
  var datum = $('#uitgave_datum').val();
  var cat = $('#uitgave_cat').val();
  var type = $(".selectedValue").text();
  var user_id = '<?php echo $user_id ;?>';
  var budget = '<?php echo  $budget['budget']; ?>';
  var id = $("#uitgave_id").val();

  var o_datum = $("#old_date").val();

  // var selected_id = $("#uitgave_cat").find('option:selected').attr('id');
  var selected_id = $( "#uitgave_cat option:selected" ).val();
  if(!prev){
    prev = selected_id;
  }
  var wijzig_data = {
    naam: naam,
    o_bedrag: o_bedrag,
    bedrag: bedrag,
    datum: datum,
    cat: cat,
    type: type,
    user: user_id,
    budget: budget,
    id: id
  } 
  var uitgave_data = {
    uitgave: naam,
    bedrag: bedrag,
    datum:datum,
    categorie: cat,
    user_id : user_id,
    type: type,
    o_date: o_datum,
    o_cat_id: prev,
    change_old : 'yes',
    o_bedrag : o_bedrag
  };

  $.ajax({
      type: 'POST',
      url: 'wijzig_uitgave.php',
      data: wijzig_data,
      dataType: 'json',
      success: function(data) {
          if(data.success) {
            $.ajax({
                type: 'POST',
                url: 'update_overzicht.php',
                data: uitgave_data,
                dataType: 'json',
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
          } else {
              //no succes
          }
      },
      error: function(err) {
          console.log(err);
      }
  });
});

function changeFunc() {
 var selectBox = document.getElementById("uitgave_cat");
 var selectedValue = selectBox.options[selectBox.selectedIndex].value;
}

$(".pm").on('click', function(){
  $('#iou').removeClass('wrong-entry');
  $(".pm").css('background-color', '#D8D8D8');
  $(".pm").removeClass('selectedValue');
  $(this).css('background-color', '#9FF781');
  $(this).addClass('selectedValue');

  var x = $(".selectedValue").text();

  if(x === 'Inkomst') {
    $("#uitgave_cat option").filter(function() {
        return $(this).text() == 'Inkomsten'; 
    }).prop('selected', true);
  }
});


$('#versturen').on('click', function(e) {
  e.preventDefault();
  var naam = $('#uitgave_naam').val();
  var bedrag = $('#uitgave_bedrag').val();
  var datum = $('#uitgave_datum').val();
  var cat = $('#uitgave_cat').val();
  var x = $(".selectedValue").text();

  if(!naam){
    $('#uitgave_naam').addClass('wrong-entry');
    $('.alert').fadeIn(1500);
    $(".alert").text("Naam van uitgave is leeg");
    setTimeout( "$('.alert').fadeOut(1500);",3000 );
    setTimeout("$('#uitgave_naam').removeClass('wrong-entry');", 3000);
  } else {
    if(!bedrag){
      $('#uitgave_bedrag').addClass('wrong-entry');
      $('.alert').fadeIn(1500);
      $(".alert").text("Het bedrag is leeg");
      setTimeout( "$('.alert').fadeOut(1500);",3000 );
      setTimeout("$('#uitgave_bedrag').removeClass('wrong-entry');", 3000);
    } else {
      if(!datum){
        $('#uitgave_datum').addClass('wrong-entry');
        $('.alert').fadeIn(1500);
        $(".alert").text("De datum is leeg");
        setTimeout( "$('.alert').fadeOut(1500);",3000 );
        setTimeout("$('#uitgave_datum').removeClass('wrong-entry');", 3000);
      } else {
        if(!cat){
          $('#uitgave_cat').addClass('wrong-entry');
          $('.alert').fadeIn(1500);
          $(".alert").text("De categorie is leeg");
          setTimeout( "$('.alert').fadeOut(1500);",3000 );
          setTimeout("$('#uitgave_cat').removeClass('wrong-entry');", 3000);

        } if(!x){
          $('#iou').addClass('wrong-entry');
          $('.alert').fadeIn(1500);
          $(".alert").text("Selecteer of het een in of uitgave is");
          setTimeout( "$('.alert').fadeOut(1500);",3000 );
          setTimeout("$('#uitgave_cat').removeClass('wrong-entry');", 3000);
        } else {
          var user_id = '<?php echo $user_id ;?>';
          var huidige_budget = '<?php echo  $budget['budget'] ;?>';

          var uitgave_data = {
            uitgave: naam,
            bedrag: bedrag,
            datum:datum,
            categorie: cat,
            user_id : user_id,
            type: x
          };

          var update_budget = {
            budget: huidige_budget,
            bedrag:bedrag,
            user_id : user_id,
            type: x
          }
          $.ajax({
              type: 'POST',
              url: 'insert_uitgave.php',
              data: uitgave_data,
              dataType: 'json',
              success: function(data) {
                  if(data.success) {
                    document.getElementById("insert_uitgave").reset();
                    $(".pm").css('background-color', 'white');
                    $(".pm").removeClass('selectedValue');
                    $.ajax({
                        type: 'POST',
                        url: 'update_budget.php',
                        data: update_budget,
                        dataType: 'json',
                        success: function(data) {
                            if(data.success) {
                              $.ajax({
                                  type: 'POST',
                                  url: 'update_overzicht.php',
                                  data: uitgave_data,
                                  dataType: 'json',
                                  success: function(data) {
                                      if(data.success) {
                                        location.reload();
                                      } else {
                                          //no succes
                                          console.log('nu socces');
                                      }
                                  },
                                  error: function(err) {
                                      console.log(err);
                                  }
                              });
                            } else {
                                //no succes
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                  } else {
                      //no succes
                  }
              },
              error: function(err) {
                  console.log(err);
              }
          });

        }
      }
    }

  }
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

</script>