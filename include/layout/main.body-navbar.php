<header class="navbar navbar-default navbar-static-top" id="top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><img style="height: 100%;" src="/public/images/logo.png" alt="HS" /></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a ui-sref="campaign_manager">Campaigns</a></li> 
                <li><a ui-sref="segment_manager">Segments</a></li> 
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