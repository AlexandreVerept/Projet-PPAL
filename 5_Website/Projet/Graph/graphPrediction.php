<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>test</title>
    <!-- Styles -->
    <style>
    #chartdiv {
      width: 100%;
      height: 500px;
    }
    </style>

    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
  </head>
<body>

<div id="chartdiv"></div>

<script type="text/javascript">

/* Chart code */

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end


// Create chart
let chart = am4core.create("chartdiv", am4charts.XYChart);
chart.paddingRight = 20;

chart.data = generateChartData();

// Créer des axes aux graphiques
let dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.baseInterval = {
  "timeUnit": "minute",
  "count": 1
};

// Changer le format de la date : Année/Mois/Jour/Heure/Minute/Seconde
chart.dateFormatter.inputDateFormat = "yyyy-MM-dd HH:mm:ss";
dateAxis.tooltipDateFormat = "yyyy-MM-dd HH:mm:ss";

// Valeur de l'axe des ordonnées
let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.tooltip.disabled = true;
valueAxis.title.text = "Prédiction de la qualité de l'air";

//Créer différentes courbes avec la fonction "series"
let series = chart.series.push(new am4charts.LineSeries());
series.dataFields.dateX = "time";
series.dataFields.valueY = "prediction";
series.tooltipText = "Indice de qualité de l'air: [bold]{valueY}[/]";
series.fillOpacity = 0.3;

// Pour la MEL 
let series2 = chart.series.push(new am4charts.LineSeries());
series2.dataFields.dateX = "time";
series2.dataFields.valueY = "valeur";
series2.tooltipText = "Indice de qualité de l'air de la MEL : [bold]{valueY}[/]";
series2.fillOpacity = 0.3;

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineY.opacity = 0;
chart.scrollbarX = new am4charts.XYChartScrollbar();
chart.scrollbarX.series.push(series);


chart.events.on("datavalidated", function () {
    dateAxis.zoom({start:0.8, end:1});
});


function generateChartData() {
    let chartData = [];

<?php
  // Connexion à la base de données
  $bdd = connexion();
  try
    {
    // Récupération des données de la Ruche souhaitée dans la base de données
    $sql_query="SELECT * From airiq;";    //Requête
  $result_query = $bdd->query($sql_query);       // Exécution de la requête

    $sql_query2="SELECT * From iq_mel;";    //Requête
  $result_query2 = $bdd->query($sql_query2); 


  // On affiche les données pour vérification
  while($row = $result_query->fetch()){
   $v1 = $row['prediction'];
   $v7 = $row['time'];
   //$v2 = $row['valeur'];
   //$v3 = $row['iq_mel.time'];

   echo "chartData.push({time : \"$v7\", prediction : \"$v1\"});";
  }


  while($row = $result_query2->fetch()){
   $v3 = $row['time'];
   $v2 = $row['valeur'];

   echo "chartData.push({time : \"$v3\", valeur: \"$v2\" });";
  }


    }catch (PDOException $e){
    echo "Connexion échouée : <font color=red><b>" . $e->getMessage()."</b></font> <br> \n";
    }
?>

console.log(chartData);
return chartData;
}

</script>
</body>
</html>
