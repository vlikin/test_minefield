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
