<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <title>Child-Tracker</title>

    <link rel="stylesheet" href="include/bootstrap.min.css">

    <link rel="stylesheet" href="stylesheets/index.stylesheet.css">

</head>

<body
    ng-app="index_app"
    ng-controller="index_ctrl"
    ng-init="init ()">

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Child-Tracker</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
            </div>
        </div>
    </nav>

    <div class="content_container distance_container panel panel-default col-xs-12 col-sm-12 col-md-4 col-md-offset-4">

        <div class="panel-heading">SET DISTANCE</div>

        <div class="panel-body">

            <form id="client_form" action="index.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" class="form-control" name="option" value="save_distance">

                <div class="input-group">
                    <input type="number" name="distance" class="form-control" placeholder="max. distance" max="30" min="2">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">ok</button>
                    </span>
                </div>

            </form>

        </div>

    </div>

    <div class="content_container panel panel-default col-xs-12 col-sm-12 col-md-4 col-md-offset-4">

        <div class="panel-heading">CLIENT-LIST</div>

        <div class="panel-body">

            <div class="client_item"
                 ng-cloak
                 ng-repeat="client in client_list | orderBy: ['state', 'image']"
                 ng-if="!(!client.image && client.distance == 0)"
                 ng-class="{ 'bg-success':  client.image && client.distance != 0,
                             'bg-info':     !client.image && client.distance != 0,
                             'bg-danger':   client.image && client.distance == 0 }">

                <div class="img-rounded" style="background-image: url('<? echo Controller::$image_path; ?>/{{ client.image }}');"
                     ng-if="client.image"></div>

                <h1 ng-if="!client.image">

                    <small>ID:</small>
                    {{ client.id }}

                </h1>

                <div class="client_item_name"
                     ng-if="client.image">

                    {{ client.name }}

                    <small>
                        ID: <strong>{{ client.id }}</strong><br>
                        present: <strong>{{ client.distance == 0 ? 'LOST' : 'OK' }}</strong><br>
                        Phone No.: <a href="tel://{{ client.phone }}">{{ client.phone }}</a>
                    </small>

                </div>

                <button type="button" class="btn btn-default btn-lg"
                        ng-if="client.image && client.distance != 0"
                        ng-click="add_client (client.id)">

                    <span class="glyphicon glyphicon-pencil"></span>

                </button>

                <button type="button" class="btn btn-default btn-lg"
                        ng-if="client.image && client.distance != 0"
                        ng-click="delete_client (client.image)">

                    <span class="glyphicon glyphicon-remove"></span>

                </button>

                <button type="button" class="btn btn-default btn-lg"
                        ng-if="!client.image && distance != 0"
                        ng-click="add_client (client.id)">

                    <span class="glyphicon glyphicon-plus"></span>

                </button>

            </div>

        </div>

    </div>

    <script src="include/jquery-2.2.1.min.js"></script>
    <script src="include/bootstrap.min.js"></script>
    <script src="include/angular.min.js"></script>

    <script src="angular/index.controller.js"></script>

</body>
</html>