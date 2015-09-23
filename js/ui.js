'use strict';

var app = angular.module('test_minefield/ui', [
  'ngMaterial'
])

.controller('FrameController', function ($scope, $window) {
  $scope.greeting = 'HI, Hello from Viktor.  ';

  $scope.dimension = 3;

  $scope.editable = false;

  $scope.toggleEditable = function() {
    alert('Hi');
    $scope.editable = !$scope.editable;
  };


  $scope.updateMatrix = function() {
    $scope.matrix = [] ;
    for (var i = 0; i < Math.pow($scope.dimension, 2); i++) {
      var cell = new Cell(i);
      $scope.matrix.push(cell);
    }
  };

  $scope.setDimension = function(dimension) {
    $scope.dimension = dimension;
    $scope.updateMatrix();
  };

  function Cell(index) {
    this.index = index;
    this.is_bomb = false;
    this.display = 'Text';

    this.toggleBomb = function() {
      return this.is_bomb = !this.is_bomb;
    };
  };

  function Bomb () {
  };

  $scope.updateMatrix();
})

.directive('cell', function() {
  return {
    restrict: 'E',
    scope: {
      cell: '='
    },
    compile: function(elem, attrs, transclude) {
      return function(scope, elem, attrs, transclude) {
        elem.text(scope.cell.state);
        elem.bind('click', function() {
          var is_bomb = scope.cell.toggleBomb();
          if (is_bomb) {
            elem.text('is_bomb');
          }
          else {
            elem.text('cell');
          }
        });
      });
    }
  };
})

.filter('range', function() {
  return function(input, total) {
    total = parseInt(total);

    for (var i = 0; i < total; i++) {
      input.push(i);
    }

    return input;
  };
})

.run(function () {});