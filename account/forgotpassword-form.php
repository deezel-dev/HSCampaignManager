<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/account/manage.php";

$email = $_GET["email"];
$token = $_GET["key"];

if (!isValidPasswordResetRequestWithToken($email, $token)) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "/index.php");
    die();
}
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.head.php" ?>
    </head>
    <body ng-app="flixAcademy">
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-navbar.php" ?>
        
        <div class="container">
            <form ng-controller="SignInController" ng-show="isLoaded">
                <div class="row visible-md-block visible-lg-block" id="logoRow" ng-hide="movies.length > 0">
                    <div class="col-xs-12" id="logoColumn">
                        <img src="/public/images/logo.png" alt="FlixAcademy Logo" />
                    </div>
                </div>
                <div class="row">
                    <div class="well col-xs-12 col-sm-5 col-centered">
                        <div class="row">
                            <div class="col-xs-12 col-centered" style="max-width: 400px;">
                                <div class="row">
                                    <h3>Reset your password</h3>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group clearfix">
                                            <label class="control-label col-xs-12">Email</label>
                                            <div class="col-xs-12">
                                                <p class="form-control-static"><?php echo htmlentities($_GET["email"]) ?></p>
                                            </div>
                                            <span class="text-danger" ng-show="emailError">{{emailError}}</span>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="control-label col-xs-12">Password <small class="required-text">(required)</small></label>
                                            <input id="txtPassword" type="password" ng-model="password" class="form-control col-xs-12" />
                                            <span class="text-danger" ng-show="passwordError">{{passwordError}}</span>
                                            <span class="help-block">Your password must be 8 characters long, contain both letters and numbers, and may contain any of the following symbols: !@#$%^&amp;*()</span>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="control-label col-xs-12">Confirm password <small class="required-text">(required)</small></label>
                                            <input id="txtConfirmPassword" type="password" ng-model="confirmPassword" class="form-control col-xs-12" />
                                            <span class="text-danger" ng-show="confirmPasswordError">{{confirmPasswordError}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 clearfix">
                                        <button class="btn btn-primary form-control" ng-click="btnChangePassword_Click()">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p style="text-align: center; font-size: 10pt;">Copyright &copy; 2015 FlixAcademy, Inc. All rights reserved.</p>
                </div>
            </form>
        </div>
        
        
        
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
        <script type="text/javascript">
            (function(angular) {
                angular.module('flixAcademy', []).controller('SignInController', ['$scope', '$http', function($scope, $http) {                    
                    // Form
                    $scope.isLoaded = true;
                    $scope.password = "";
                    $scope.confirmPassword = "";
                    $scope.emailError = null;
                    $scope.passwordError = null;
                    $scope.confirmPasswordError = null;
                    
                    //
                    // Event
                    
                    $scope.btnChangePassword_Click = function($event) {
                        $scope.emailError = null;
                        $scope.passwordError = null;
                        $scope.confirmPasswordError = null;
                        
                        if ($scope.password.length < 8 || !/[a-zA-Z]/.test($scope.password) || !/[0-9]/.test($scope.password) || !/^[a-zA-Z0-9!@#\$%\^&*\(\)]*$/.test($scope.password))
                            $scope.passwordError = "Invalid password";
                        if ($scope.password != $scope.confirmPassword)
                            $scope.confirmPasswordError = "Passwords don't match";
                        
                        if ($scope.emailError || $scope.passwordError || $scope.confirmPasswordError)
                            return;
                        
                        $http.post("//<?php echo($_SERVER['HTTP_HOST']) ?>/account/forgotpassword-post.php", {
                            email: "<?php echo $_GET["email"]; ?>",
                            key: "<?php echo $_GET["key"]; ?>",
                            password: $scope.password,
                            confirmPassword: $scope.confirmPassword})
                        .success(function(data, status, headers, config) {
                            if (data.status == "error")
                                for (var i = 0; i < data.errors.length; i++)
                                    switch (data.errors[i].field) {
                                        case "email":
                                            $scope.emailError = data.errors[i].message;
                                            break;
                                        case "password":
                                            $scope.passwordError = data.errors[i].message;
                                            break;
                                        case "confirmPassword":
                                            $scope.confirmPasswordError = data.errors[i].message;
                                            break;
                                    }
                            else {
                                window.alert("Password reset successfully.");
                                window.location = "http://<?php echo($_SERVER['HTTP_HOST']) ?>/account/signin.php";
                            }
                        }).error(function(data, status, headers, config) {
                            
                        });
                    }
                }]);
            })(window.angular);
        </script>
    </body>
</html>