<!DOCTYPE html>
<html>
<head>
    <title>Ilegal Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <style>
    h1 {
        text-align: center;
        font-size: 80px;
        margin-bottom: 60px;
    }
    </style>
</head>
<body ng-app="ilegalreporter">
<div class="container">
    <div class="content">
        <h1>Ilegal Reporter</h1>
        <table ng-controller="ReporterController" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Ktp</th>
                    <th>Description</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Photo</th>
                <tr>
            </thead>
            <tbody>
                <tr ng-repeat="reporter in reporters">
                    <td>@{{ reporter.name }}</td>
                    <td>@{{ reporter.ktp }}</td>
                    <td>@{{ reporter.description }}</td>
                    <td>@{{ reporter.latitude }}</td>
                    <td>@{{ reporter.longitude }}</td>
                    <td>
                         <img ng-src="@{{ reporter.photo }}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Angular js -->>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.0/angular.min.js"></script>

<!-- Pusher -->
<script src="//js.pusher.com/3.0/pusher.min.js"></script>

<!-- Angular Pusher library -->
<script src="//cdn.jsdelivr.net/angular.pusher/latest/pusher-angular.min.js"></script>

<script>
    angular.module('ilegalreporter', ['pusher-angular']);

    angular.module('ilegalreporter')
        .controller('ReporterController', ReporterController);

    ReporterController.$inject = ['$scope', '$pusher'];

    function ReporterController($scope, $pusher) {
        var client = new Pusher("{{ env('PUSHER_KEY') }}");
        var pusher = $pusher(client);
        var ilegalReportChannel = pusher.subscribe("ilegal_reports");

        $scope.reporters = [];

        ilegalReportChannel.bind("App\\Events\\NewIlegalReport", function(reporter) {
            $scope.reporters.push(reporter);
        });
    }
</script>
<script>
</script>
</body>
</html>
