<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Webapp Menu</title>
  <link href="css/index.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Public+Sans">
</head>

<body>
  <script src="js/script.js"></script>
  <main>
    <br>
    <header>
      <div>
        <h1>Menu</h1>
      </div>
    </header>
    <br>
    <h3>Categories</h3>
    <div class="flex1">
      <div class="flexitem1" onclick="window.location.href='/sites.php'" style="background:url('/images/map.png')">
        <p>Sites</p>
      </div>
      <div class="flexitem1" onclick="window.location.href='/profile.php'" style="background:url('/images/crowd.jpg');background-size:800%;">
        <p>Profile</p>
      </div>
      <div class="flexitem1" onclick="window.location.href='/reports.php'" style="background:url('/images/trash.jpg');background-size:cover;">
        <p>Report</p>
      </div>
      <div class="flexitem1" onclick="window.location.href='/info.php'" style="background:url('/images/info.jpg');background-size:100%;">
        <p>About</p>
      </div>
    </div>
    <br>
    <h3>Things To Know</h3>
    <div class="flex1">
      <div class="flexitem1" style="background:url('/images/forest.jpg');background-size:cover;"><p>Environment</p></div>
      <div class="flexitem1" style="background:url('/images/construction.jpg');background-size:cover;"><p>Human Safety</p></div>
      <div class="flexitem1" style="background:url('/images/bear.jpg');background-size:cover;"><p>Wilderness Safety</p></div>
    </div>
  </main>
  <div class="navfoot">
    <button onclick="window.location.href='/'">Menu</button>
    <button onclick="window.location.href='/profile.php'"></button>
    <button onclick="window.location.href='/sites.php'">Sites</button>
  </div>
</body>

</html>