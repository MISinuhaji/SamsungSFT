<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Webapp</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Public+Sans">
  <link href="css/cleanup.css" rel="stylesheet" type="text/css" />
</head>

  <body> 
    <script>
      let currentDiv = 1;
      function showDiv(divId) {
        document.getElementById(currentDiv).style.display = 'none';
        document.getElementById(divId).style.display = 'block';
        currentDiv = divId;
        if (divId === 3) {
          startTimer(10 * 60, document.querySelector('#timer'));
        }
      }
      window.onload = function() {
        for(let i = 2; i <= 5; i++) {
          document.getElementById(i).style.display = 'none';
        }
      }
      function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        let tickTock = true;
        let intervalId = setInterval(function () {
          minutes = parseInt(timer / 60, 10);
          seconds = parseInt(timer % 60, 10);
          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;
          display.textContent = minutes + ":" + seconds;
          if (timer <= 0) {
            clearInterval(intervalId);
            showDiv(4);
            let audio = new Audio('audio/mixkit-positive-notification-951.wav'); // replace 'audio_file.mp3' with the path to your audio file
            audio.play();
          } else if (tickTock) {
            let ttAudio = new Audio('audio/tick.mp3'); // replace 'audio_file.mp3' with the path to your audio file
            ttAudio.play();
            tickTock = false;
          } else {
            let ttAudio = new Audio('audio/tock.mp3');
            ttAudio.play();
            tickTock = true;
          }
          timer--;
        }, 1000);
      }
    </script>

    <div id="1">
      <h1>Follow the steps in order to clean the site.</h1>
      <button onclick="showDiv(2)">Next</button>
    </div>
    <div id="2">
      <h1>Set a timelapse video of the site.</h1>
      <input type="file" accept="image/*" id="myFile" name="filename">
      <button onclick="showDiv(3)">Next</button>
    </div>
    <div id="3">
      <h1>Clean for 30 minutes, start the video.</h1>
      <p id="timer">Timer Start!</p>
    </div>
    <div id="4">
      <h1>Take a photo of the site, after cleaning.</h1>
      <input type="file" accept="image/*" id="myFile" name="filename"> <button onclick="showDiv(5)">Finish</button>
    </div>
    <div id="5">
      <h1>You're done! Click the button to return to the menu.</h1>
      <button onclick="window.location.href = 'index.php'">Return</button>
    </div>
  </body>






</html>