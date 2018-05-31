<?php

if (isset($_POST['image'])) {
  $imageLocation = "img/img" . uniqid(true) . ".jpg";
  $img = urldecode($_POST['image']);
  $img = str_replace(' ', '+', $img);
  $decodedImage = base64_decode($img, true);
    if ($decodedImage !== false) {
       
          if (@imagecreatefromstring($decodedImage)) {
            $file = fopen($imageLocation, "x");
            if ($file) {
              fwrite($file, base64_decode($img));
              fclose($file);
              file_put_contents("img/list.txt", $imageLocation."\r\n", FILE_APPEND);
            }
          }
          else {
            echo "The image is broken...";
          }     
      }
  else {
    echo "There was an issue with uploading";
  }
    
  exit;
}

?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
      .add-video {
        clear: both;
        display: block;
      }
      
      #snapshot {
        display: none;
      }
      
      #user-videos img {
        margin: 10px;
        padding: 10px;
        background-color: #ddd;
        border: 1px solid #aaa;
      }
      
      button.add-video,
      div#snapshot * {
        margin: 0 auto;
        text-align: center;
      }
    </style>
  </head>

  <body>
    <div class="text-center">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-success">
          <div class="panel-heading">Our users have shared with us:</div>
          <div class="panel-body" id="user-images"></div>

          <div id="user-video"></div>
          <div>
            <p>Want to share a camera image too?</p>
            <button class='btn btn-success btn-lg add-video'>Start Camera</button>
          </div>
          <div id='snapshot'>
            <p>Your snapshot is shown below:</p>
            <canvas width="400" height="400" id="canvas"></canvas>
          </div>
        </div>
      </div>
    </div>
    <script>
      //get all user images
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "img/list.txt");
      xhr.onreadystatechange = function (e) {
        if (xhr.status === 200 && xhr.readyState === 4) {
          var images = xhr.responseText.split("\r\n");
          images.forEach(function (val) {
            if (val.trim()) {
              var img = document.createElement("img");
              img.src = val;
              img.className = "img-thumbnail img-circle img-responsive"
              document.getElementById("user-images").appendChild(img);
            }
          })
        }
      }
      xhr.send();
      var recording = 0,
        mediaStream = null;
      document.getElementsByClassName("add-video")[0].addEventListener("click", function () {
        if (recording === 0) {
          this.innerHTML = "Take The Snapshot";
          if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                audio: false,
                video: true
              })
              .then(onUserStream)
              .catch(onUserRevokePermission)
          } else {
            navigator.theUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
              navigator.mozGetUserMedia || navigator.msGetUserMedia || null;
            if (navigator.theUserMedia) {
              navigator.theUserMedia({
                video: true,
                audio: false
              }, onUserStream, onUserRevokePermission);

            } else {
              alert("User media API not supported");
            }
          }

        } else if (recording === 1) {
          document.getElementById("snapshot").style.display = "block";
          recording = -1;
          this.className += " disabled";
          var canvas = document.querySelector('#canvas'),
            ctx = canvas.getContext("2d");
          ctx.drawImage(document.querySelector("video"), 0, 0, canvas.width, canvas.height);
          var dataUrl = canvas.toDataURL('image/jpg', 1) //second arg is quality; 1 is the maximum; 
          dataUrl = dataUrl.slice(dataUrl.indexOf(',') + 1);
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "");
          xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          xhr.onreadystatechange = function (e) {
            if (xhr.status === 200 && xhr.readyState === 4) {
              if (!xhr.responseText) {
                alert("Your image was successfully uploaded");
              } else {
                alert("An error occurred when uploading" + xhr.responseText);
              }
            }
          }
          xhr.send("image=" + encodeURIComponent(dataUrl));
          if (mediaStream.stop) {
            mediaStream.stop();
          } else {
            mediaStream.getVideoTracks()[0].stop();
          }
          this.innerHTML = "Snapshot Added";

        }
      });

      function onUserRevokePermission(e) {
        alert("We are sorry that you are not trusting us with your camera!");
      }
      function onUserStream(stream) {


        var video = document.createElement("video");
        video.src = window.URL.createObjectURL(stream);
        mediaStream = stream;
        recording = 1;
        document.getElementById("user-video").appendChild(video);
        video.play();


      }
    </script>

  </body>

  </html>