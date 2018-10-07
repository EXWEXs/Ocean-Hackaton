<?php
header('Content-Type: text/html; charset=utf-8');
include 'config.php';
include 'satellites.php';
?>

<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/icomoon.css" type="text/css">
    <link rel="stylesheet" href="/perfect-scrollbar.min.css" type="text/css">
    <link rel="stylesheet" href="/style.css" type="text/css">

    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="/scripts/satellite.min.js"></script>
    <script src="/script-loader.php"></script>
    
    <title>SatWatch | OceanHackaton</title>
    
  </head>
  <body>
  <div id="no-webgl">
    You need <a href="http://caniuse.com/#feat=webgl">WebGL</a> and <a href="http://caniuse.com/#feat=webworkers">Web Worker</a> support for visit this site. 
  </div>
  <div id="canvas-holder">
    <canvas id="canvas"></canvas>
    <div id="menu-left" class="menubar">
      <div id="search-holder" class="menu-item">
        <span class="icon-search"></span>
        <input type="text" id="search"></input>
      </div>
      <div id="menu-groups" class="menu-item">
        <div class="menu-title">Groups</div>
        <ul id="groups-display" class="dropdown submenu">
          <li data-group="<clear>" class="clear-option">Clear</li>
          <li data-group="GPSGroup">GPS</li>
          <li data-group="IridiumGroup">Iridium</li>
          <li data-group="GlonassGroup">GLONASS</li>
          <li data-group="GalileoGroup">Galileo</li>
          <li data-group="Iridium33DebrisGroup">Iridium 33 Collision Debris</li>
          <li data-group="WestfordNeedlesGroup">Westford Needles</li>
          <li data-group="SpaceXGroup">SpaceX</li>
        </ul>
      </div>
    </div>
    <div id="menu-right" class="menubar">
      <div id="menu-help" class="menu-item">
        <div class="menu-title"><a href="/addSat.php">Add Satellite</a></div>
      </div>
      <div id="menu-help" class="menu-item">
        <div class="menu-title">Help</div>
        <div id="help-box" class="menubox submenu">
          <span class="box-header">Legend</span>
          <ul id="legend">
            <li>
               <img class="dot" src="/dot-red.png"></img>
               Satellite
             </li>
            <li>
              <img class="dot" src="/dot-blue.png"></img>
              Rocket body
            </li>
            <li>
              <img class="dot" src="/dot-grey.png"></img>
              Debris
            </li>
          </ul>
          <ul id="controls-info">
            <li>
              Left/Right click and drag to rotate camera
            </li>
            <li> Mousewheel to scroll </li>
            <li>
              Left click to select an object
            </li>
          </ul>
        </div>
      </div>
      <div id="menu-about" class="menu-item">
        <div class="menu-title">About</div>
        <div id="about-box" class="menubox submenu">
          <span class="box-header">SatWatch</span>
          <p>SatWatch is a realtime 3D map of satellites in Earth orbit, visualized using WebGL.</p>
          
          <p>Supported by the amazing website <a href="https://github.com/jeyoder/ThingsInSpace"> ThingsInSpace</a> by James Yoder.
          Make with love for the Ocean Hackaton 2018.

          The website updates daily with orbit data from <a href="n2yo.com">N2YO.com</a> 
          and uses the excellent <a href="https://github.com/shashwatak/satellite-js">satellite.js</a> Javascript library
          to calculate satellite positions.</p>
        </div>
      </div>
    </div>
      <div id="search-results"></div>
    <div id="sat-hoverbox">(none)</div>
    <div id="sat-infobox">
      <div id="sat-info-title">This is a title</div>
      <div id="all-objects-link" class="link">Find all objects from this launch...</div>
        <div class="sat-info-row">
          <div class="sat-info-key">Int'l Designator</div>
          <div class="sat-info-value" id="sat-intl-des">1998-067A</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Type</div>
          <div class="sat-info-value" id="sat-type">PAYLOAD</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Apogee</div>
          <div class="sat-info-value" id="sat-apogee">100 km</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Perigee</div>
          <div class="sat-info-value" id="sat-perigee">100 km</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Inclination</div>
          <div class="sat-info-value" id="sat-inclination">123.45°</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Altitude</div>
          <div class="sat-info-value" id="sat-altitude">100  km</div>
        </div>
        <div class="sat-info-row">
          <div class="sat-info-key">Velocity</div>
          <div class="sat-info-value" id="sat-velocity">100  km/s</div>
        </div>
  
      </div>
    </div>

    <div id="zoom-controls">
      <div id="zoom-in" class="zoom-button">+</div>
      <div id="zoom-out" class="zoom-button">-</div>
    </div>
    <div id="load-cover">
      <div id="loader">
        <div id="spinner"></div>
        <div id="loader-text">
          Downloading resources...
        </div>
      </div>
    </div>
  </div>
  <script>
    $('#locateSatButton').click(function(){
      var date = $('#myDate').val();
      var time = $('#myTime').val();
      if (time != "" && date != "") {
        var epoch = new Date(date+" "+time);
      }
    })
  </script>
  </body>
</html>