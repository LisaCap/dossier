<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">BackOffice_Lokisalle</a>
        </div>

        <!---- Sidebar de gauche -------------------------->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li <?php if(false){echo 'class="active"';}?> >
                    <a href="statistiques.php"><i class="fa fa-fw fa-dashboard"></i> Statistiques</a>
                </li>
                <li>
                    <a href="gestion_salles.php"><i class="fa fa-fw fa-bar-chart-o"></i> Gestion des salles</a>
                </li>
                <li>
                    <a href="gestion_produits.php"><i class="fa fa-fw fa-table"></i> Gestion des produits</a>
                </li>
                <li>
                    <a href="gestion_membres.php"><i class="fa fa-fw fa-edit"></i> Gestion des membres</a>
                </li>
                <li>
                    <a href="gestion_avis.php"><i class="fa fa-fw fa-desktop"></i> Gestion des avis</a>
                </li>
                <li>
                    <a href="gestion_commandes.php"><i class="fa fa-fw fa-wrench"></i> Gestion des commandes</a>
                </li>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>