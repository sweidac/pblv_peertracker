//app module
var app = angular.module ('client_app', []);

//index controller
app.controller ('client_ctrl', function ($scope, $http, $location) {

    $scope.init = function (client_id) {

        //get client-list
        $http.get ('rest/clients/?client_id=' + client_id).success (function (client) {

            //parse list
            $scope.client = angular.fromJson (client);
            $scope.client = $scope.client[client_id];

        });

    }

});