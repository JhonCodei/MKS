<!DOCTYPE html>
<html>
<head>
<?php print APP_ICON ?>
	<title>404 - Error</title>
	<style type="text/css">
	img.bg {
        min-height: 100%;
        min-width: 1024px;
          
        width: 100%;
        height: auto;
          
        position: fixed;
        top: 0;
        left: 0;
      }
      
      /* @media screen and (max-width: 1024px) { 
        img.bg {
          left: 50%;
          margin-left: -512px;   
        }
      } */

	
		/*#page-wrap { 
        position: relative; z-index: 2; width: 400px; margin: 50px auto; padding: 20px; background: white; -moz-box-shadow: 0 0 20px black; -webkit-box-shadow: 0 0 20px black; box-shadow: 0 0 20px black; }*/
		 body > p { 
        font-size:8em;
        text-align:center;
        padding:12%;
        color:#E2EFF1;
        font-family: Varela round;
        text-indent: 40px;
        position: relative; 
        }
	/*font-size:8em;text-align:center;color:#E2EFF1;font-family:Varela round;*/
	</style>
</head>
<body>

 <img onclick="window.history.back(1);" style="cursor:pointer;" class="bg" src="<?php print APP_PUBLIC;?>public/assets/images/error/MKS-404.jpg" alt="DATAERROR">
  <!-- <p style=""><a href="<?php #print web_path();?>">¡Error! <br> 404</p></a> -->

  
</body>
</html>