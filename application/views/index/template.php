<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>Leflet test</title>
        <meta name="author" content="Rayaz" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Leaflet test example" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="" type="text/css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
    </head>
    
    <body  >
     
	    <div class="container">
	    
		    <div class="row">
		      
		      <!-- Static navbar -->
		      <nav class="navbar navbar-default">
		        <div class="container-fluid">
		          <div class="navbar-header">
		            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		              <span class="sr-only">Скрыть меню</span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		            </button>
		            <a class="navbar-brand" href="#">Приложение "Карта"</a>
		          </div>
		          <div id="navbar" class="navbar-collapse collapse">
		            <ul class="nav navbar-nav">
		              <li><a href="/index.php/index/index">Главная</a></li>
		              <li><a href="/index.php/index/points">Точки</a></li>
		            </ul>
		            
		          </div><!--/.nav-collapse -->
		        </div><!--/.container-fluid -->
		      </nav>
      
			<?=!empty($content) ? $content : '';?>
			</div>
		</div>
    </body>
</html>