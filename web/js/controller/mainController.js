'use strict';
angular.module('evecompare')
.controller('mainController', [ '$scope', '$http', function($scope, $http){
    $scope.search = {
        region: null,
        type: null
    };

    $scope.ref_data = {
        types: [],
        regions: []
    };

    $scope.loading = true;
    $scope.loading_submit = false;

    $scope.$watch('ref_data', function(d){
        if (d.types.length && d.regions.length){
            $scope.loading = false;
        }
    }, true);

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
        $scope.ref_data.regions = data.data;
    });

    $http.get('/mineral_types').then(function(data){

        var formattedTypes = [];

        angular.forEach(data.data, function(d){
            formattedTypes.push({
                id: d[0],
                name: d[1]
            });
        });

        $scope.ref_data.types = formattedTypes;
    });

    $scope.submitMarketForm = function(){
        if ($scope.search.region === null || $scope.search.type === null){
            return;
        }

        $scope.loading_submit = true;

        $http.post('/market_history', $scope.search).then(function(data){
            $scope.data = data.data;
            $scope.loading_submit = false;
        });


    }
}]);