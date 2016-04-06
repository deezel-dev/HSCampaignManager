<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_init.php";
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
                    
                    
                    
                    <div class="well col-xs-12 col-sm-5 col-centered" ng-hide="requestSignUp">
                        <div class="row">
                            <div class="col-xs-12 col-centered" style="max-width: 400px;">
                                <div class="row">
                                    <h3>
                                        Sign in&nbsp;<small>&nbsp;&nbsp;<a href="#" ng-click="lnkSignUp_Click()">...or <strong>sign up</strong></a></small>
                                    </h3>
                                    <span class="text-danger" ng-show="signInError">{{signInError}}</span>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12">Email</label>
                                    <input id="txtSignInEmail" type="email" ng-model="signInEmail" class="form-control col-xs-12" />
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12">Password <small><a href="#" ng-click="lnkForgotPassword_Click()">Forgot your password?</a></small></label>
                                    <input id="txtSignInPassword" type="password" ng-model="signInPassword" class="form-control col-xs-12" />
                                </div>
                                <?php /*<div class="row">
                                    <div class="col-xs-12 clearfix">
                                        <label class="checkboxk-inline">
                                            <input type="checkbox" ng-model="signInRememberMe" />
                                            Remember me?
                                        </label>
                                    </div>
                                </div>*/ ?>
                                <div class="row">
                                    <div class="col-xs-12 clearfix">
                                        <button class="btn btn-primary form-control" ng-click="btnSignIn_Click()">Sign In</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="well col-xs-12 col-lg-10 col-centered" ng-show="requestSignUp">
                        <div class="row">
                            <h3>
                                Sign up&nbsp;<small>&nbsp;&nbsp;<a href="#" ng-click="lnkSignIn_Click()">...or <strong>sign in</strong></a></small>
                            </h3>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12 cold-md-6">Email <small class="required-text">(required)</small></label>
                                    <input id="txtSignUpEmail" type="email" ng-model="signUpEmail" class="form-control col-xs-12 col-md-6" />
                                    <span class="text-danger" ng-show="signUpEmailError">{{signUpEmailError}}</span>
                                    <span class="help-block">We do not share your email address with members of the website, or anyone else for that matter.</span>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12 cold-md-6">Display name <small class="required-text">(required)</small></label>
                                    <input id="txtSignUpDisplayName" type="text" ng-model="signUpDisplayName" class="form-control col-xs-12 col-md-6" />
                                    <span class="text-danger" ng-show="signUpDisplayNameError">{{signUpDisplayNameError}}</span>
                                    <span class="help-block">This name is visible to other members of the website and must be alphanumeric.</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12 cold-md-6">Password <small class="required-text">(required)</small></label>
                                    <input id="txtSignUpPassword" type="password" ng-model="signUpPassword" class="form-control col-xs-12 col-md-6" />
                                    <span class="text-danger" ng-show="signUpPasswordError">{{signUpPasswordError}}</span>
                                    <span class="help-block">Your password must be at least 6 characters long, contain both letters and numbers, and may contain any of the following symbols: !@#$%^&amp;*()</span>
                                </div>
                                <div class="form-group clearfix">
                                    <label class="control-label col-xs-12 cold-md-6">Confirm password <small class="required-text">(required)</small></label>
                                    <input id="txtSignUpConfirmPassword" type="password" ng-model="signUpConfirmPassword" class="form-control col-xs-12 col-md-6" />
                                    <span class="text-danger" ng-show="signUpConfirmPasswordError">{{signUpConfirmPasswordError}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-6 col-md-offset-6 clearfix">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" ng-model="signUpTermsAccepted" />
                                            I agree to the Terms of Use, available <a href="/terms.php" target="_blank">here</a>.
                                        </label>
                                    </div>
                                    <div class="text-danger" ng-show="signUpTermsAcceptedError">
                                        {{signUpTermsAcceptedError}}
                                    </div>
                                </div>
                                <button class="btn btn-primary form-control" ng-click="btnSignUp_Click()">Sign Up</button>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
                <div class="row">
                    <p style="text-align: center; font-size: 10pt;">Copyright &copy; 2015 FlixAcademy, Inc. All rights reserved. <a href="/terms.php">Terms of Use</a></p>
                </div>
            </form>
        </div>
        
        
        
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/include/layout/main.body-scripts.php" ?>
        <script type="text/javascript">
            (function(angular) {
                angular.module('flixAcademy', []).controller('SignInController', ['$scope', '$http', function($scope, $http) {                    
                    // Form
                    $scope.isLoaded = true;
                    $scope.requestSignUp = false;
                    $scope.signInEmail = "";
                    $scope.signInPassword = "";
                    $scope.signInRememberMe = false;
                    $scope.signInError = null;
                    $scope.signUpEmail = "";
                    $scope.signUpDisplayName = "";
                    $scope.signUpPassword = "";
                    $scope.signUpConfirmPassword = "";
                    $scope.signUpTermsAccepted = false;
                    $scope.signUpEmailError = null;
                    $scope.signUpDisplayNameError = null;
                    $scope.signUpPasswordError = null;
                    $scope.signUpConfirmPasswordError = null;
                    $scope.signUpTermsAcceptedError = null;
                    
                    //
                    // Events
                    
                    $scope.lnkSignUp_Click = function($event) {
                        $scope.requestSignUp = true;
                        $scope.signUpEmail = $scope.signInEmail;
                        $scope.signInEmail = "";
                        $scope.signInPassword = "";
                        $scope.signInError = null;
                    }
                    
                    $scope.lnkSignIn_Click = function($event) {
                        $scope.requestSignUp = false;
                        $scope.signInEmail = $scope.signUpEmail;
                        $scope.signUpEmail = "";
                        $scope.signUpDisplayName = "";
                        $scope.signUpPassword = "";
                        $scope.signUpConfirmPassword = "";
                        $scope.signUpTermsAccepted = false;
                        $scope.signUpEmailError = null;
                        $scope.signUpDisplayNameError = null;
                        $scope.signUpPasswordError = null;
                        $scope.signUpConfirmPasswordError = null;
                        $scope.signUpTermsAcceptedError = null;
                    }
                    
                    $scope.lnkForgotPassword_Click = function($event) {
                        if ($scope.signInEmail.length > 0)
                            window.location = "//<?php echo($_SERVER['HTTP_HOST']) ?>/account/forgotpassword.php?email=" + $scope.signInEmail;
                        else
                            $scope.signInError = "Please specify your email to reset your password";
                    }
                    
                    $scope.btnSignIn_Click = function($event) {
                        $scope.signInError = null;
                        
                        if ($scope.signInEmail.length == 0 || !/^([\w-\.]+@([\w-]+\.)+[\w-]{2,30})?$/.test($scope.signInEmail))
                            $scope.signInError = "Invalid email address";
                        if ($scope.signInPassword.length < 8)
                            $scope.signInError = "Invalid password";                        
                        if ($scope.signInError)
                            return;
                        
                        $http.post("//<?php echo($_SERVER['HTTP_HOST']) ?>/account/signin-post.php", {
                            email: $scope.signInEmail,
                            password: $scope.signInPassword})
                        .success(function(data, status, headers, config) {
                            if (data.status == "error")
                                for (var i = 0; i < data.errors.length; i++)
                                    $scope.signInError = $scope.signInError ? $scope.signInError + "<br />" + data.errors[i] : data.errors[i];
                            else {
                                <?php if (isset($_GET["follow"])) { ?>
                                    window.location = "http://<?php echo($_SERVER['HTTP_HOST']) . $_GET["follow"] ?>";
                                <?php } else { ?>
                                    window.location = "http://<?php echo($_SERVER['HTTP_HOST']) ?>/account/index.php";
                                <?php } ?>
                            }
                        }).error(function(data, status, headers, config) {
                            
                        });
                    }
                    
                    $scope.btnSignUp_Click = function($event) {
                        $scope.signUpEmailError = null;
                        $scope.signUpDisplayNameError = null;
                        $scope.signUpPasswordError = null;
                        $scope.signUpConfirmPasswordError = null;
                        $scope.signUpTermsAcceptedError = null;
                        
                        if ($scope.signUpEmail.length == 0 || !/^([\w-\.\+]+@([\w-]+\.)+[\w-]{2,30})?$/.test($scope.signUpEmail))
                            $scope.signUpEmailError = "Invalid email address";
                        if (!/^[a-zA-Z0-9][a-zA-Z0-9][a-zA-Z0-9]+$/.test($scope.signUpDisplayName))
                            $scope.signUpDisplayNameError = "Invalid name, must be at least 3 characters long and alphanumeric";
                        if ($scope.signUpPassword.length < 6 || !/[a-zA-Z]/.test($scope.signUpPassword) || !/[0-9]/.test($scope.signUpPassword) || !/^[a-zA-Z0-9!@#\$%\^&*\(\)]*$/.test($scope.signUpPassword))
                            $scope.signUpPasswordError = "Invalid password";
                        if ($scope.signUpPassword != $scope.signUpConfirmPassword)
                            $scope.signUpConfirmPasswordError = "Passwords don't match";
                        if (!$scope.signUpTermsAccepted)
                            $scope.signUpTermsAcceptedError = "You must accept the Terms of Use before submitting a Flix suggestion or additional content.";
                        
                        if ($scope.signUpEmailError || $scope.signUpDisplayNameError || $scope.signUpPasswordError || $scope.signUpConfirmPasswordError || $scope.signUpTermsAcceptedError)
                            return;
                        
                        $http.post("//<?php echo($_SERVER['HTTP_HOST']) ?>/account/create-post.php", {
                            email: $scope.signUpEmail,
                            displayName: $scope.signUpDisplayName,
                            password: $scope.signUpPassword,
                            confirmPassword: $scope.signUpConfirmPassword,
                            isTermsAccepted: $scope.signUpTermsAccepted})
                        .success(function(data, status, headers, config) {
                            if (typeof data.status === "undefined") {
                                window.alert(
                                    "We're sorry; but an error has occurred while creating your account. " +
                                    "If this problem persists, please contact staff@flixacademy.com.");
                            }
                            else if (data.status == "error")
                                for (var i = 0; i < data.errors.length; i++)
                                    switch (data.errors[i].field) {
                                        case "email":
                                            $scope.signUpEmailError = data.errors[i].message;
                                            break;
                                        case "displayName":
                                            $scope.signUpDisplayNameError = data.errors[i].message;
                                            break;
                                        case "password":
                                            $scope.signUpPasswordError = data.errors[i].message;
                                            break;
                                        case "confirmPassword":
                                            $scope.signUpConfirmPasswordError = data.errors[i].message;
                                            break;
                                        case "isTermsAccepted":
                                            $scope.signUpTermsAcceptedError = data.errors[i].message;
                                            break;
                                    }
                            else
                                window.location = "http://<?php echo($_SERVER['HTTP_HOST']) ?>/account/index.php";
                        }).error(function(data, status, headers, config) {
                            
                        });
                    }
                }]);
            })(window.angular);
        </script>
    </body>
</html>