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

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["averageBalance"] . "</td>";
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

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalToday"] . "</td>";
    }

    $sql = "SELECT COUNT(transaction_id) as totalYesterday FROM tbltransaction where DAY(timestamp) = DAY(CURRENT_DATE()-1)";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalYesterday"] . "</td>";
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

      function drawChart() {

        const jsondata = <?php echo $json_data;?>;
        
        var data = google.visualization.arrayToDataTable([
          ['Account Type', 'Total Number'],
          ['Basic Accounts', <?php echo $data[0]?>],
          ['Partial Accounts',<?php echo $data[1]?>],
          ['Full Accounts',  <?php echo $data[2]?>]
        ]);

        var options = {
          title: 'Total Accounts'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div style="margin-left: auto; margin-right: auto;" id="piechart" style="width: 900px; height: 500px;"></div>
  </body>