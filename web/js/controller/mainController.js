'use strict';
angular.module('evecompare')
.controller('mainController', [ '$scope', '$http', function($scope, $http){

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

            console.log(formattedTypes);

            $scope.types = formattedTypes;
        });
    }]);