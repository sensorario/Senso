<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <title>{{title}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="{{description}}" />
        <meta name="author" content="{{author}}" />

        <script src="/cache/bootstrap/js/bootstrap.js"></script>
        <link href="/cache/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/jquery-ui-git.js"></script>
        <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/jquery-ui-git.css" />
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body><div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="{path{homepage}}">{{project_name}}</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="{path{homepage}}">Home</a></li>
                    <li><a href="{path{manual_homepage}}">Manual</a></li>
                    <li><a href="{path{contatti}}">Contact</a></li>
                    <li><a href="{path{blog_dashboard}}">Blog</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{path{homepage}}">Home</a></li>
                            <li><a href="{path{manual_homepage}}">Manual</a></li>
                            <li class="divider"></li>
                            <li class="nav-header">Nav header</li>
                            <li><a href="{path{contatti}}">Contatti</a></li>
                            <li><a href="{path{blog_dashboard}}">Blog</a></li>
                            <li><a href="{path{manual_credits}}">Credits</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form pull-right">
                    <input class="span2" type="text" placeholder="Email">
                    <input class="span2" type="password" placeholder="Password">
                    <button type="submit" class="btn">Sign in</button>
                </form>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
        {start content}
        {end content}
        <script src="/cache/bootstrap/js/bootstrap-transition.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-alert.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-modal.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-dropdown.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-scrollspy.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-tab.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-tooltip.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-popover.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-button.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-collapse.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-carousel.js"></script>
        <script src="/cache/bootstrap/js/bootstrap-typeahead.js"></script>
    </body>
</html>
