app.controller('reports', ['$scope', '$http', function($scope, $http) {
  
  $scope.report = function(from, to, category)
  {
    $http.post(APP_URL+"/admin/reports", { from: from, to: to, category: category }).then(function( response ) {
       if(!$scope.category) {
    	$scope.users_report = response.data;
    	$scope.rooms_report = false;
    	$scope.reservations_report = false;
       }
       if($scope.category == 'rooms') {
    	$scope.users_report = false;
    	$scope.rooms_report = response.data;
    	$scope.reservations_report = false;
       }
       if($scope.category == 'reservations') {
    	$scope.users_report = false;
    	$scope.rooms_report = false;
       	$scope.reservations_report = response.data;
       }
    });
  };

  $scope.print = function(category)
  {
    category = (!category) ? 'users' : category;
    var prtContent = document.getElementById(category);
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
  };

  $('.date').datepicker({ 'dateFormat': 'dd-mm-yy'});
  
}]);