<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
       <nav class="navbar navbar-default">
           <div class="row" style="margin: 0 auto !important;">
             <div class="head-p1 col-md-6" style="z-index: 20;">
                     <div class="navbar-header" style="width: 100% !important;">
                 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collap">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                  </button>
                <a class="navbar-brand" style="float: left !important;" href="#" >Socio</a>
                        <div class="search-div" style="max-width: 40% !important;float: left;margin-top: 10px ">
                            <input type="text" id="search" class="search-input form-control" style="width:140% !important;"> 
                            <ul class="list-group suggest-box" style="position: absolute;width: 40% !important;">
                          
                            </ul>
                        </div>
                           
                 </div>
            </div>
             <div class="head-p2 col-md-6">
                 <div class="collapse navbar-collapse" id="collap">
                    <ul class="nav navbar-nav">
               <li><a>TIMELINE</a></li>
               <li><a>MESSAGES</a></li>
               <li><a>NOTIFICATIONS</a></li>
               <li><div class="dropdown">
                     <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">User<span class="caret"></span></button>  
                   <ul class="dropdown-menu">
                   <li><a>My Profile</a></li>
                   <li><a>Timeline</a></li>
                    <li><a>Messages</a></li>
                    <li><a>Notifications</a></li>
                    <li><a>My Account</a></li>
                    <li><a>Logout</a></li>
                   </ul>
                   </div></li>
              </ul>
              </div>   
             </div>   
           </div>     
       </nav>
    </header>