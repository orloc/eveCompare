'use strict';
angular.module('evecompare')
.controller('mainController', [ '$scope', '$http', function($scope, $http){
    $scope.search = {
        region: null,
        type: null
    };

    $scope.data = [];

    $scope.options = {
        chart: {
            type: "lineWithFocusChart",
            height: 450,
            margin: {
                top: 20,
                right: 20,
                bottom: 55,
                left: 55
            },
            "duration": 100,
            x: function (d) {
                return d[0];
            },
            y: function (d) {
                return d[1];
            },
            xAxis: {
                axisLabel: "Time (Day)",
                showMaxMin: false,
                axisLabelDistance: -5,
                staggerLabels: true,
                tickFormat: function (d) {
                    return d3.time.format('%x')(new Date(d));
                }
            },
            yAxis: {
                axisLabel: "ISK",
                axisLabelDistance: -5,
                tickFormat: function (d) {
                    return d3.format(',.2f')(d);
                }

            },
            x2Axis: {
                axisLabel: "Time (Day)",
                showMaxMin: false,
                axisLabelDistance: -5,
                tickFormat: function (d) {
                    return d3.time.format('%x')(new Date(d));
                }
            }
        }
    };

    $http.get('/regions').then(function(data){
        $scope.regions = data.data;
    });

    $http.get('/mineral_types').then(function(data){

        var formattedTypes = [];

        angular.forEach(data.data, function(d){
            formattedTypes.push({
                id: d[0],
                name: d[1]
            });
        });

        $scope.types = formattedTypes;
    });

    $scope.submitMarketForm = function(){
        if ($scope.search.region === null || $scope.search.type === null){
            return;
        }

        $http.post('/market_history', $scope.search).then(function(data){
            $scope.data = data.data;
        });


    }
}]);