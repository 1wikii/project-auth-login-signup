<?php 

  session_start();

  if( isset($_SESSION['username']) ){

    $username = $_SESSION['username'];

    $isLoggedIn = true;

  }

  else{

    $isLoggedIn = false;

  }

?>

<link rel="stylesheet" href="./style/header.css" ></link>

<header>

    <nav>

      <ul class="left-ul">

        <svg id="logo" viewbox="0 0 100 100" xlmns="http://www.w3.org/2000/svg"> 

          <a class="page-icon" href="#"> <image href="./assets/web-icon.png" width="100%" height="100%"> </a>

        </svg>

      </ul>

        <ul class="right=ul">

          <?php if($isLoggedIn): ?>

            <li> <?php echo "<span class='username' > Welcome, <i>$username</i> </span> ";?>  

                <a class="log-out" href="log-out.php"> Log Out</a> </li>

          <?php endif; ?>

        </ul>

    </nav>

</header>





