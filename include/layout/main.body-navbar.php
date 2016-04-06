<header class="navbar navbar-default navbar-static-top" id="top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><img style="height: 100%;" src="/public/images/logo.png" alt="HectorSantosMinistries" /></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">

                <li><a ui-sref="main({mode: 1})">Home</a></li> 
                <li><a href="">About Us</a></li>
                <li><a href="">Training</a></li>
                <li><a href="">Links</a></li>
                <li><a href="">Itinerary</a></li>
                <li><a href="">Dreams</a></li>
                <li><a href="">Contact Us</a></li>
                
                
                <?php if (isset($_SESSION[SESSION_PROFILE_ID])) { ?>                
                    <li><a ui-sref="flix_main({mode: 2})">My Playlist</a></li>
                   <!--  <li <?php //if ($_SERVER['PHP_SELF'] == "/flix/search.php?playlist=true") echo "class=\"active\"" ?>><a href="/flix/search.php?playlist=true">My Playlist</a></li>-->
                <?php } ?>
                
                
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Follow Us <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        //<li><a href="http://flixacademy.weebly.com/blog.html">Blog</a></li>
                        //<li><a href="http://flixacademy.weebly.com/press.html">Press</a></li>
                        //<li class="divider"></li>
                        ?>
                        <li><a href="http://twitter.com/FlixAcademy" target="_blank">Twitter</a></li>
                        <li><a href="http://facebook.com/FlixAcademy" target="_blank">Facebook</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION[SESSION_PROFILE_ID])) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Hello,&nbsp;<strong><?php echo htmlentities($_SESSION[SESSION_PROFILE_DISPLAYNAME]) ?></strong>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/account/signout.php">Sign Out</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li <?php if ($_SERVER['PHP_SELF'] == "/account/signin.php") echo "class=\"active\"" ?>><a href="/account/signin.php">Sign In or Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</header>