var app = angular.module('app', ['ui.router', 'ui.bootstrap'])
    .config(['$urlRouterProvider', '$stateProvider', function ($urlRouterProvider, $stateProvider) {

        $urlRouterProvider.otherwise('/main');

        $stateProvider

        // INDEX STATES AND NESTED VIEWS ========================================
            .state('main', {
                url: '/main',
                templateUrl: '/site/main.php',
                params: {
                    mode: 1
                }
            })

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('campaign_manager', {
            url: '/campaign_manager',
            templateUrl: '/cm/campaign_manager.html'
        })

        // INDEX STATES AND NESTED VIEWS ========================================
        .state('segment_manager', {
            url: '/segment_manager',
            templateUrl: '/cm/segment_manager.html'
        })

    } ])
    .service('dataService', function ($http, $rootScope, $window, $q, $state) {

        var dataService = this;


        dataService.isUser = false;
        dataService.profile_id = -1;
        dataService.rootPath = "";
        dataService.listMode = 1;

        dataService.dreamListLoaded = false;

        dataService.dreamList = [];
        dataService.filteredMovies = [];
        dataService.payload = {};

        dataService.showMovies = false;
        dataService.showListView = true;
        dataService.showMovie = false;
        dataService.showSuggestion = false;
        dataService.x = 0;
        dataService.y = 0;


        dataService.movie = {};


        dataService.setProfileId = function (id) {
            dataService.profile_id = id;
        }

        dataService.getProfileId = function () {
            return dataService.profile_id;
        }

        dataService.getDreams = function () {
            var promise = $http.get('/api/json/get-all-dreams.json').then(function (response) {
                dataService.dreamList = response.data.movies;
                dataService.broadcastDreamListUpdate();
                return dataService.dreamList;
            });
            return promise;
        }

        dataService.broadcastDreamListUpdate = function () {
            dataService.dreamListLoaded = true;
            $rootScope.$broadcast('dreamListUpdate');
        };


        dataService.showSuggestionView = function (_showSuggestion) {
            dataService.showSuggestion = _showSuggestion;
            dataService.broadcastShowSuggestionUpdate();
        }

        dataService.showMovieView = function (_showMovie) {
            dataService.showMovie = _showMovie;
            dataService.broadcastShowMovieUpdate();
        }


        dataService.showMovieView = function (_showMovie, x, y) {
            dataService.x = x;
            dataService.y = y;
            dataService.showMovie = _showMovie;
            dataService.broadcastShowMovieUpdate();
        }

        dataService.showView = function (showList) {
            dataService.showListView = showList;
            dataService.broadcastShowListUpdate();
        }

        dataService.showMovieList = function (showMovies) {
            dataService.showMovies = showMovies;
            dataService.broadcastShowMoviesUpdate();
        }

        dataService.broadcastShowSuggestionUpdate = function () {
            $rootScope.$broadcast('showSuggestion');
        };

        dataService.broadcastShowMovieUpdate = function () {
            $rootScope.$broadcast('showMovieUpdate');
        };

        dataService.broadcastShowMoviesUpdate = function () {
            $rootScope.$broadcast('showMoviesUpdate');
        };

        dataService.broadcastShowListUpdate = function () {
            $rootScope.$broadcast('showListView');
        };

        dataService.broadcastPayloadUpdate = function () {
            $rootScope.$broadcast('payloadUpdate');
        };

        dataService.broadcastVendorListUpdate = function () {
            $rootScope.$broadcast('vendorListUpdate');
        };

        dataService.broadcastTopicListUpdate = function () {
            dataService.topicListLoaded = true;
            $rootScope.$broadcast('topicListUpdate');
        };

        dataService.broadcastMovieListUpdate = function () {
            dataService.movieListLoaded = true;
            $rootScope.$broadcast('movieListUpdate');
        };

        dataService.broadcastMovieUpdate = function () {
            $rootScope.$broadcast('movieUpdate');
        };

        dataService.setPayload = function (_payload) {
            dataService.payload = _payload;
            dataService.broadcastPayloadUpdate();
        }

        dataService.getPayload = function () {
            return dataService.payload;
        }

        dataService.setMovie = function (_movie) {
            //alert('setting movie to ' + _movie.title);
            dataService.movie = _movie;
            dataService.broadcastMovieUpdate();
            dataService.logMovie(_movie);
        }

        dataService.logMovie = function (movie) {

            var activity_type = "MOVIE_VIEW";
            var product_id = movie.product_id;
            var topic_id = -1;
            var data = "movie: " + movie.title + " - " +
                       "topic: " + dataService.payload.selectedCategory.id + " - " +
                       "min_len: " + dataService.payload.minimumLength + " - " +
                       "max_len: " + dataService.payloadmaximumLength + " - " +
                       "min_age: " + dataService.payload.minimumAge + " - " +
                       "text_filter: " + dataService.payload.movieTitleFilter;

            dataService.logActivity(activity_type,
                                    product_id,
                                    topic_id,
                                    data);

        }

        dataService.logVendorLink = function (movie, vendor) {

            var activity_type = "VENDOR_LINK";
            var product_id = movie.product_id;
            var topic_id = -1;
            var data = "movie: " + movie.title + " - " +
                       "vendor: " + vendor.name + " - " +
                       "vendor_url: " + vendor.url;

            dataService.logActivity(activity_type,
                                    product_id,
                                    topic_id,
                                    data);

        }

        dataService.getMovie = function () {
            //alert('from service getting movie to ' + dataService.movie.title);
            return dataService.movie;
        }

        dataService.getTopics = function () {
            var promise = $http.get('/api/json/get-topics-subtopics.json').then(function (response) {
                dataService.topicList = dataService.getTopicList(response.data);
                dataService.broadcastTopicListUpdate();
                return dataService.topicList;
            });
            return promise;
        }

        dataService.getTopicList = function (data) {
            var categories = [];
            var topics = data.topics;

            categories.push({
                "id": -100,
                "name": "Any"
            });

            angular.forEach(topics, function (topic, index) {

                if (angular.isDefined(topic)) {

                    var _topic = {
                        "id": topic.id,
                        "name": topic.name.toUpperCase(),
                        "type": topic.type,
                        "parent_id": topic.parent_id
                    };

                    categories.push(_topic);
                }

                var subtopics = topic.SubTopics;
                angular.forEach(subtopics, function (subtopic, index) {

                    if (angular.isDefined(subtopic)) {

                        if (subtopic != null && subtopic.id != null && subtopic.name != null && subtopic.type != null && subtopic.parent_id != null) {
                            var _subtopic = {
                                "id": subtopic.id,
                                "name": ".  " + subtopic.name,
                                "type": subtopic.type,
                                "parent_id": subtopic.parent_id
                            };
                            categories.push(_subtopic);
                        }

                    }

                });

                /*categories.push({
                "id": -1,
                "name": "-------------"
                });*/

            });

            return categories;
        }

        dataService.getVendors = function () {
            var promise = $http.get('/api/json/get-vendors.json').then(function (response) {
                dataService.vendorList = dataService.getVendorList(response.data);
                dataService.broadcastVendorListUpdate();
                return dataService.vendorList;
            });
            return promise;
        }

        dataService.getVendorList = function (data) {
            var _vendors = [];
            var vendors = data.vendors;

            _vendors.push({
                "id": -100,
                "name": "Any"
            });

            angular.forEach(vendors, function (vendor, index) {
                if (angular.isDefined(vendor)) {
                    _vendors.push(vendor);
                }
            });

            return _vendors;

        }

        dataService.getMovies = function () {
            var promise = $http.get('/api/json/get-all-movies.json').then(function (response) {
                dataService.movieList = response.data.movies;
                dataService.broadcastMovieListUpdate();
                return dataService.movieList;
            });
            return promise;
        }

        dataService.getDeviceSize = function () {
            var w = $window.innerWidth;
            if (w < 768) {
                return 'xs';
            } else if (w < 992) {
                return 'sm';
            } else if (w < 1200) {
                return 'md';
            } else {
                return 'lg';
            }
        }

        dataService.getMovieById = function (product_id) {
            var deferred = $q.defer();
            $http.get('api/flix_academy_admin_api.php?action=getFlixMovie&api_token=flixteam2014&product_id=' + product_id)
                .success(function (data) {
                    deferred.resolve(data);
                })
                .error(function (msg, code) {
                    deferred.reject(msg);
                });

            return deferred.promise;
        }

        dataService.addMovieToPlaylist = function (movie) {

            if (dataService.isUser) {

                if (dataService.profile_id > 0) {

                    $http.post("/api/flix/addToUserPlaylist.php", {
                        product_id: movie.product_id,
                        profile_id: dataService.profile_id
                    })
                        .success(function (data, status, headers, config) {
                            alert("'" + movie.title + "' added to your playlist");
                        }).error(function (data, status, headers, config) {
                            alert("error!");
                        });

                } else {
                    alert("Please sign in or create an account to add this movie to your playlist.");
                }

            } else {
                alert("Please sign in or create an account to add this movie to your playlist.");
            }

        };

        dataService.rateMovie = function (rating, movie) {

            $http.post("/api/flix/addFlixGrading.php", {
                product_id: movie.product_id,
                profile_id: dataService.profile_id,
                score: rating
            })
                .success(function (data, status, headers, config) {
                    alert("Thank you for submitting a grade for '" + movie.title + "'");
                }).error(function (data, status, headers, config) {
                    alert("error!");
                });

        };

        dataService.logActivity = function (_activity_type, _product_id, _topic_id, _data) {

            $http.post("/api/flix/logActivity.php", {
                activity_type: _activity_type,
                product_id: _product_id,
                profile_id: dataService.profile_id,
                topic_id: _topic_id,
                data: _data
            })
                .success(function (data, status, headers, config) {
                    //alert("Thank you for your submission we will review it very soon.");
                    return true;
                }).error(function (data, status, headers, config) {
                    //alert("error " + status);
                    return false;
                });
        }

        dataService.sendFeedback = function (_product_id, _feedback_type, _feedback) {

            var isUser = 0;

            if (dataService.profile_id > 0) {
                isUser = 1;
            }

            $http.post("/api/flix/addFeedback.php", {
                product_id: _product_id,
                profile_id: dataService.profile_id,
                is_flix_user: isUser,
                feedback_type: _feedback_type,
                feedback: _feedback
            })
                .success(function (data, status, headers, config) {
                    alert("Thank you for your submission we will review it very soon.");
                    return true;
                }).error(function (data, status, headers, config) {
                    return false;
                });
        }

        dataService.submitMovieSuggestion = function (entryName, entryLink, subjects) {

            if ((entryName == null || entryName == "") &&
                (entryLink == null || entryLink == "")) {
                alert("A link or title is required.");
                return;
            }

            if (dataService.isUser) {

                $http.post("/flix/submit-post.php", {
                    entryName: entryName,
                    entryLink: entryLink,
                    entrySubject: subjects,
                    profileID: dataService.profile_id
                })
                    .success(function (data, status, headers, config) {
                        alert("Thank you for submitting a suggestion! " + "We review and add suggestions on a regular basis.");
                    }).error(function (data, status, headers, config) {
                        //alert(status);
                    });

            } else {
                alert("Please sign in to suggest movies!");
            }

        }

        dataService.getMovieImage = function (movie) {

            var imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt.png';

            if (dataService.getDeviceSize() == 'xs') {
                imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt_small.png';
            }

            if (movie.product_image != null && movie.product_image.length > 0) {
                imageUrl = movie.product_image;
            }

            return imageUrl;

        }

        dataService.openMovie = function (_movie) {
            var params = {movie: _movie};
            $state.go('flix_main_movie', params);
        }

        dataService.openRemix = function (_movie, _topic, _flix_remix_id) {

            if (dataService.isUser) {

                var params = { movie: _movie,
                    topic: _topic,
                    flix_remix_id: _flix_remix_id,
                    previousState: { name: $state.current.name }
                };

                $state.go('flix_remix_submission', params);

            } else {

                alert("Please sign in or create an account to create a FlixREMIX lesson plan for this movie.");

            }


        }

        dataService.getNextRemixID = function () {

            var deferred = $q.defer();
            var url = "/api/flix/flix_academy_api.php?action=getNextFlixRemixId&api_token=flixteam2014";
            $http.get(url)
                .success(function (data) {
                    var id = data.nextID[0].nextVal;
                    deferred.resolve(id);
                })
                .error(function (msg, code) {
                    deferred.reject(msg);
                });

            return deferred.promise;


        }

        dataService.saveRemix = function (_flix_remix_id, movie, lessonName, subject_id, details) {

            $http.post("/api/flix/addFlixRemix.php", {
                flix_remix_id: _flix_remix_id,
                profile_id: dataService.profile_id,
                product_id: movie.product_id,
                title: movie.title,
                lesson_name: lessonName,
                topic: subject_id
            })
                .success(function (data, status, headers, config) {
                    dataService.saveRemixDetails(details, _flix_remix_id);
                    alert("Thank you for submitting this lesson.  We will review it shortly and post it soon.");
                    //$window.close();
                }).error(function (data, status, headers, config) {
                    alert("error!");
                });

        }

        dataService.saveRemixDetails = function (details, _flix_remix_id) {

            for (i = 0; i < details.length; i++) {
                dataService.saveRemixDetail(details[i], _flix_remix_id);
            }

        }

        dataService.saveRemixDetail = function (detail, _flix_remix_id) {
            $http.post("/api/flix/addFlixRemixDetails.php", {
                flix_remix_id: _flix_remix_id,
                seq_id: detail.seq_id,
                time: detail.time,
                commentary: detail.commentary,
                link: detail.link,
                image: detail.image,
                commentary_type: detail.commentary_type,
                option_1: detail.option_1,
                option_2: detail.option_2,
                option_3: detail.option_3,
                option_4: detail.option_4,
                correct_option: 1

            })
                .success(function (data, status, headers, config) {

                }).error(function (data, status, headers, config) {
                    alert("error!");
                });
        }

        dataService.getFlixRemix = function (remixID) {

            var deferred = $q.defer();
            var url = "/api/flix/get-remix.php?remix_id=" + remixID;
            $http.get(url)
                .success(function (data) {
                    deferred.resolve(data);
                })
                .error(function (msg, code) {
                    deferred.reject(msg);
                });

            return deferred.promise;


        }

        dataService.getPlaylist = function () {

            var promise = $http.get('/api/flix/get-playlist.php?profile_id=' + dataService.profile_id).then(function (response) {
                dataService.playlist_ids = response.data.product_ids;
                return dataService.playlist_ids;
            });
            return promise;

        }

    })
    .controller("indexCtrl", ['$scope', '$window', 'dataService', function ($scope, $window, dataService) {

        $scope.isUser = false;
        $scope.profileId = -1;
        $scope.rootUrl = $window.location.protocol + "//" + $window.location.host;
        dataService.rootPath = $scope.rootUrl;
        $scope.showSuggestion = false;


        $scope.setProfileData = function (profileId) {

            var isUser = false;

            if (profileId > 0) {
                isUser = true;
            }

            $scope.isUser = isUser;
            $scope.profileId = profileId;

            dataService.isUser = isUser;
            dataService.setProfileId(profileId);
        }



    } ])