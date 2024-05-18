<?php
// include_once("connect.php");
include("includes/header.php");

?>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Number of Basic Accounts</th>
      <th scope="col">Total Number of Partial Accounts</th>
      <th scope="col">Total Number of Full Accounts</th>
    </tr>
  </thead>
  <tbody>
    <?php


    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $basic="";
    $data = array();
    $sql = "SELECT COUNT(account_id) as basicAccs FROM tblacc where acc_type = 'basic'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["basicAccs"] . "</td>";
      $basic = $row["basicAccs"];
      $data[] = intval($row["basicAccs"]);
    }

    $sql = "SELECT COUNT(account_id) as partialAccs FROM tblacc where acc_type = 'partial'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["partialAccs"] . "</td>";
      
      $data[] = intval($row["partialAccs"]);
    }

    $sql = "SELECT COUNT(account_id) as fullAccs FROM tblacc where acc_type = 'full'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["fullAccs"] . "</td>";
      
      $data[] = intval($row["fullAccs"]);
    }

    $json_data = json_encode($data);

    
    

    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Average Balance</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT AVG(total_balance) as averageBalance FROM tblacc";
    $result = mysqli_query($connection, $sql);
    $averageData = array();
    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["averageBalance"] . "</td>";
      $averageData [] = intval($row["averageBalance"]);
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Average Flow of Money</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT AVG(amount) as averageFlow FROM tbltransaction";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["averageFlow"] . "</td>";
      $averageData [] = intval($row["averageFlow"]);
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Transactions Made Today</th>
      <th scope="col">Total Transactions Made Yesterday</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT COUNT(transaction_id) as totalToday FROM tbltransaction where DAY(timestamp) = DAY(CURRENT_DATE())";
    $result = mysqli_query($connection, $sql);
    $transactionsData = array();
    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalToday"] . "</td>";
      $transactionsData[] = intval($row["totalToday"]);
    }

    $sql = "SELECT COUNT(transaction_id) as totalYesterday FROM tbltransaction where DAY(timestamp) = DAY(CURRENT_DATE()-1)";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalYesterday"] . "</td>";
      $transactionsData[] = intval($row["totalYesterday"]);
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Transactions Made This Year</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT COUNT(transaction_id) as totalToday FROM tbltransaction where Year(timestamp) = Year(CURRENT_DATE())";
    $result = mysqli_query($connection, $sql);

   
    

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalToday"] . "</td>";
    }
    ?>
  </tbody>
</table>

  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);

      function drawChart() {
        
        var data = google.visualization.arrayToDataTable([
          ['Account Type', 'Total Number'],
          ['Basic Accounts', <?php echo $data[0]?>],
          ['Partial Accounts',<?php echo $data[1]?>],
          ['Full Accounts',  <?php echo $data[2]?>]
        ]);

        var options = {
          title: 'Total Accounts',
          backgroundColor: "#9b8621"
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

      function drawChart2() {
      var data = google.visualization.arrayToDataTable([
        ["Day", "Transactions", { role: "style" } ],
        ["Yesterday", <?php echo $transactionsData[1]?>, "silver"],
        ["Today", <?php echo $transactionsData[0]?>, "gold"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Day-to-day Transactions",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        backgroundColor: "#9b8621"
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchartTransaction"));
      chart.draw(view, options);
    }

      function drawChart3() {
      var data = google.visualization.arrayToDataTable([
        ["Average", "Money", { role: "style" } ],
        ["Average Balance", <?php echo $averageData[0]?>, "silver"],
        ["Average Flow", <?php echo $averageData[1]?>, "gold"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Average Balance vs Average Flow",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
        backgroundColor: "#9b8621"
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchartAverage"));
      chart.draw(view, options);
    }
    </script>
  </head>
  <body>
    <div style="margin-left: auto; margin-right: auto; margin-bottom: 20px; width: 900px; height: 500px;" id="piechart" ></div>
    <div style="margin-left: auto; margin-right: auto; margin-bottom: 20px; width: 600px; height: 400px;" id="columnchartAverage"></div>
    <div style="margin-left: auto; margin-right: auto; margin-bottom: 20px; width: 600px; height: 400px;" id="columnchartTransaction"></div>
  </body>

  