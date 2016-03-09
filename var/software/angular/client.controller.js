//app module
var app = angular.module ('client_app', []);

//index controller
app.controller ('client_ctrl', function ($scope, $http, $location) {

    $scope.init = function (client_id) {

        //get client
        $http.get ('rest/clients/?client_id=' + client_id).success (function (client) {

            //parse list
            $scope.client = angular.fromJson (client);
            $scope.client = $scope.client[client_id];

        });

        //resize image
        $scope.resize_image_and_submit = function () {

            if (typeof window.FileReader !== 'function') {

                alert ("The file API isn't supported on this browser yet.");
                return;

            }

            var image_input = document.getElementById('client_image_input');

            if (!image_input)
                alert ("Um, couldn't find the imgfile element.");
            else
            if (!image_input.files)
                alert ("This browser doesn't seem to support the `files` property of file inputs.");
            else
            if (!image_input.files[0])
                alert ("Please select a file before clicking 'Load'");
            else {

                var file = image_input.files[0];

                $scope.fr = new FileReader ();
                $scope.fr.onload = $scope.resize_image_and_submit_file_onload;
                $scope.fr.readAsDataURL (file);

            }

        };

        $scope.resize_image_and_submit_file_onload = function () {

            $scope.image = new Image ();
            $scope.image.onload = $scope.resize_image_and_submit_image_onload;
            $scope.image.src = $scope.fr.result;

        };

        $scope.resize_image_and_submit_image_onload = function () {

            var canvas = document.getElementById ('image_canvas');

            var context = canvas.getContext ('2d');
                context.clearRect (0, 0, canvas.width, canvas.height);

            var MAX_WIDTH   = 400;
            var MAX_HEIGHT  = 400;
            var width       = $scope.image.width;
            var height      = $scope.image.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width    = width;
            canvas.height   = height;

            context.drawImage ($scope.image, 0, 0, width, height);

            document.getElementById ('image_data').value = canvas.toDataURL ("image/jpeg", 0.7);

            document.getElementById ('client_form').submit ();

        };

    }

});