//app module
var app = angular.module ('index_app', []);

//index controller
app.controller ('index_ctrl', function ($scope, $http, $location, $browser) {

    $scope.init = function () {

        //get client-list
        $http.get ('rest/clients').success (function (client_list) {

            //generate list
            $scope.client_list = [];

            for (var key in client_list)
                $scope.client_list.push (client_list[key]);

        });

    };

    $scope.add_client = function (client_id) {

        location.href = 'client.php?client_id=' + client_id;

    };

    $scope.delete_client = function (image) {

        location.href = 'index.php?option=delete&image=' + image;

    };

});