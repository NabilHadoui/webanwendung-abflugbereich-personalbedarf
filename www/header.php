<?php 
// header.php
//session_start();
?>
   <div class="container mt-4 ">
     <!-- Navigation : Links zu allen Hauptseiten und Logout --> 
     <!-- 'nav': Definiert das Navigationselement -->
  	 <!-- 'nav-pills': mach die Links wie runde Schaltflächen -->
   	 <!-- 'flex-column': Ordnet Links vertikal auf kleinen Bildschirmen an -->
     <!-- 'flex-lg-row': Ordnet Links horizontal auf großen Bildschirmen an -->
     <!-- align-items-center: Links horizontal zentrieren. -->
     <nav class="nav nav-pills flex-column flex-lg-row  align-items-center">               
         <!-- 'flex-sm-fill': füllt den verfügbaren Platz auf kleinen Bildschirmen -->
         <!-- 'text-sm-center': Zentriert den Text auf kleinen Bildschirmen -->
         <!-- 'nav-link': Definiert einen Link innerhalb der Navigation -->
         <!-- 'fs-3': Setzt die Schriftgröße auf 3 -->
         <!-- 'fw-bolder': Macht den Text fetter -->          
         <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/admin/dashboard.php">Home</a>
         <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/admin/fluege_index.php">Flüge</a>
         <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/admin/checkin_index.php">Checkin</a>
         <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/admin/security_index.php">Security</a>
         <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder " href="/admin/gate_index.php">Gate</a>
         <!-- Logout-Button -->
          <a class="flex-sm-fill text-sm-center nav-link fs-3 fw-bolder logout-button" href="/logout.php" >Logout</a>
   	</nav>
   
   </div> 
 