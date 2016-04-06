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
        .state('flix_main_movie', {
            url: '/flix_main_movie',
            templateUrl: '/site/flix_main_movie.php',
            controller: 'movieViewCtrl',
            params: {
                movie: null
            }
        })


        // INDEX STATES AND NESTED VIEWS ========================================
        .state('flix_remix_submission', {
            url: '/flix_remix_submission',
            templateUrl: '/site/flix_remix_submission.html',
            controller: 'flixRemixCtrl',
            params: {
                movie: null,
                topic: null,
                flix_remix_id: -1,
                previousState: null
            }

        })
    } ])
    .service('dataService', function ($http, $rootScope, $window, $q, $state) {

        var dataService = this;


        dataService.isUser = false;
        dataService.profile_id = -1;
        dataService.rootPath = "";
        dataService.listMode = 1;

        dataService.topicListLoaded = false;
        dataService.vendorListLoaded = false;
        dataService.movieListLoaded = false;

        dataService.topicList = [];
        dataService.vendorList = [];
        dataService.movieList = [];
        dataService.playlist_ids = [];
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
    .controller("flixRemixCtrl", ['$scope', '$state', '$stateParams', '$http', '$timeout', '$window', 'dataService', function ($scope, $state, $stateParams, $http, $timeout, $window, dataService) {

        var movie = $stateParams.movie;
        var topic = $stateParams.topic;
        var remix_id = $stateParams.flix_remix_id;

        dataService.setMovie(movie);

        $scope.coverArt = "";
        $scope.movie = movie;
        $scope.selected = topic;
        $scope.flix_id = movie.product_id;
        $scope.flix_remix_id = remix_id;
        $scope.hideButtons = false;
        $scope.flixSubjects = [];
        $scope.isExistingRemix = 0;
        $scope.quizOptions = [];
        $scope.needsShuffle = false;
        $scope.myPage = {
            "height": 100
        };

        $scope.getPageHeight = function () {
            return 100; //$window.innerHeight;
        }

        //fill subject list
        if (dataService.topicListLoaded) {
            $scope.flixSubjects = dataService.topicList;
        } else {
            $scope.getTopics();
        }

        $scope.getTopics = function () {
            dataService.getTopics();
        }

        $scope.$on('topicListUpdate', function () {
            $scope.flixSubjects = dataService.topicList;
        });


        $scope.init = function () {
            //check if accessing an existing remix
            if ($scope.flix_remix_id > 0) {
                //load remix by user and remix id
                //check if user to allow edits???

                $scope.hideButtons = true;
                $scope.needsShuffle = true;
                $scope.isExistingRemix = 1;

                dataService.getFlixRemix($scope.flix_remix_id).then(function (data) {
                    $scope.flixHeader = data.flix_remix_data[0];
                    $scope.flix_remix_id = $scope.flixHeader.flix_remix_id;
                    $scope.profile_id = $scope.flixHeader.profile_id;
                    $scope.flix_id = $scope.flixHeader.product_id;
                    $scope.movieTitle = $scope.flixHeader.title;
                    $scope.lessonName = $scope.flixHeader.lesson_name;
                    $scope.selectedSubjectID = $scope.flixHeader.topic;

                    var entries = $scope.flixHeader.details.remixDetailArray;
                    var remixEntries = [];

                    if ($scope.needsShuffle) {
                        //rebuild 1 by 1 if needs shuffle
                        angular.forEach(entries, function (entry, index) {
                            remixEntries.push($scope.shuffleEntry(entry))
                        });

                        $scope.remixEntries = remixEntries;

                    } else {
                        $scope.remixEntries = entries;
                    }


                    dataService.getMovieById($scope.flixHeader.product_id).then(function (movie) {
                        $scope.movie = movie;
                    });
                });


            } else {
                //start new entry
                $scope.coverArt = dataService.getMovieImage($scope.movie);
                dataService.getNextRemixID().then(function (id) {
                    $scope.flix_remix_id = id;
                });

            }

        }

        $scope.init();

        $scope.initTopic = function (topic_id, topic_name) {

            var topic = {
                id: topic_id,
                name: topic_name
            };
            $scope.selected = {
                flixSubject: topic
            };
        };


        $scope.getColumnWidth = function () {

            var width = "col-lg-8 col-md-8 col-sm-7 col-xs-12";

            if ($scope.isExistingRemix == 1) {
                width = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
            }

            return width;

        }

        $scope.setSelectedSubjectID = function (value) {
            $scope.selectedSubjectID = value;
        };

        $scope.loadRemix = function (remixID) {
            alert('load remix # ' + remixID);
            dataService.getFlixRemix(remixID).then(function (data) {
                //$scope.flixHeader = data.data.flix_remix_data[0];
                //alert("$scope.flixHeader.title = " + $scope.flixHeader.title);
                //$scope.flix_remix_id = $scope.flixHeader.flix_remix_id;
                //$scope.profile_id = $scope.flixHeader.profile_id;
                //$scope.flix_id = $scope.flixHeader.product_id;
                //$scope.movieTitle = $scope.flixHeader.title;
                //$scope.lessonName = $scope.flixHeader.lesson_name;
                //$scope.selectedSubjectID = $scope.flixHeader.topic;
                //dataService.getMovieById($scope.flixHeader.product_id).then(function (movie) {
                //    $scope.movie = movie;
                //});
            });

        };

        $scope.loadRemixX = function (remixID) {

            var url = "/api/flix/flix_academy_api.php?action=getFlixRemix&api_token=flixteam2014&remix_id=" + remixID;
            $http.get(url).then(function (data) {

                $scope.flixHeader = data.flix_remix_data[0];

                alert($scope.flixHeader.lesson_name);

                //$scope.getFlixMovie($scope.flixHeader.product_id);


                $scope.flix_remix_id = $scope.flixHeader.flix_remix_id;
                $scope.profile_id = $scope.flixHeader.profile_id;
                $scope.flix_id = $scope.flixHeader.product_id;
                $scope.movieTitle = $scope.flixHeader.title;
                $scope.lessonName = $scope.flixHeader.lesson_name;
                $scope.selectedSubjectID = $scope.flixHeader.topic;



                dataService.getMovieById($scope.flixHeader.product_id).then(function (movie) {
                    $scope.movie = movie;
                });

                var url = "/api/flix/flix_academy_api.php?action=getRemixDetails&api_token=flixteam2014&remix_id=" + remixID;
                $http.get(url).then(function (data) {

                    //$scope.entries = data.data.remixDetailArray;
                    var entries = data.data.remixDetailArray;
                    var remixEntries = [];

                    if ($scope.needsShuffle) {
                        //rebuild 1 by 1 if needs shuffle
                        angular.forEach(entries, function (entry, index) {
                            remixEntries.push($scope.shuffleEntry(entry))
                        });

                        $scope.remixEntries = remixEntries;

                    } else {
                        $scope.remixEntries = entries;
                    }



                });

            });

        };

        $scope.hours = ["00", "01", "02", "03", "04", "05"];

        $scope.hours = ["00", "01", "02", "03", "04", "05"];

        $scope.minutes = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
            "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
            "21", "22", "23", "24", "25", "26", "27", "28", "29", "30",
            "31", "32", "33", "34", "35", "36", "37", "38", "39", "40",
            "41", "42", "43", "44", "45", "46", "47", "48", "49", "50",
            "51", "52", "53", "54", "55", "56", "57", "58", "59"
        ];

        $scope.seconds = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
            "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
            "21", "22", "23", "24", "25", "26", "27", "28", "29", "30",
            "31", "32", "33", "34", "35", "36", "37", "38", "39", "40",
            "41", "42", "43", "44", "45", "46", "47", "48", "49", "50",
            "51", "52", "53", "54", "55", "56", "57", "58", "59"
        ];

        $scope.timeHR = "00";
        $scope.timeMIN = "00";
        $scope.timeSEC = "00";

        $scope.initialValue = 28;

        $scope.entryID = 1;
        $scope.entries = [];
        $scope.commentary_type = 1;
        $scope.options = [];
        //$scope.quizView = { showQuiz: false };

        $scope.addEntry = function (entry) {
            $scope.entries.push(entry);
        }

        $scope.getMinutes = function () {

            var min = "" + $scope.timeMIN;

            if ($scope.timeMIN < 10) {
                min = "0" + $scope.timeMIN;
            }

            return min;

        };

        $scope.getSeconds = function () {

            var sec = "" + $scope.timeSEC;

            if ($scope.timeSEC < 10) {
                sec = "0" + $scope.timeSEC;
            }

            return sec;

        };

        $scope.shuffleArray = function (array) {
            var m = array.length,
                t, i;

            // While there remain elements to shuffle
            while (m) {
                // Pick a remaining element…
                i = Math.floor(Math.random() * m--);

                // And swap it with the current element.
                t = array[m];
                array[m] = array[i];
                array[i] = t;
            }

            return array;
        }

        $scope.getEntry = function () {

            $scope.quizOptions = [];
            $scope.quizOptions[0] = $scope.option_1;
            $scope.quizOptions[1] = $scope.option_2;
            $scope.quizOptions[2] = $scope.option_3;
            $scope.quizOptions[3] = $scope.option_4;

            if ($scope.needsShuffle) {
                $scope.quizOptions = $scope.shuffleArray($scope.quizOptions);
            }


            var entry = {
                "seq_id": $scope.entries.length,
                "time": $scope.timeHR + ":" + $scope.timeMIN + ":" + $scope.timeSEC,
                "commentary": $scope.getValue($scope.commentary),
                "link": $scope.getValue($scope.link),
                "image": $scope.getValue($scope.image),
                "commentary_type": $scope.commentary_type,
                "option_1": $scope.getValue($scope.quizOptions[0]),
                "option_2": $scope.getValue($scope.quizOptions[1]),
                "option_3": $scope.getValue($scope.quizOptions[2]),
                "option_4": $scope.getValue($scope.quizOptions[3]),
                "correct_option": 1

            };

            return entry;

        };

        $scope.shuffleEntry = function (entry) {
            $scope.quizOptions = [];
            $scope.quizOptions[0] = entry.option_1;
            $scope.quizOptions[1] = entry.option_2;
            $scope.quizOptions[2] = entry.option_3;
            $scope.quizOptions[3] = entry.option_4;
            $scope.quizOptions = $scope.shuffleArray($scope.quizOptions);

            entry.option_1 = $scope.quizOptions[0];
            entry.option_2 = $scope.quizOptions[1];
            entry.option_3 = $scope.quizOptions[2];
            entry.option_4 = $scope.quizOptions[3];

            return entry;

        };

        $scope.clearEntryFields = function () {

            //$scope.commentary = "";
            $scope.link = "";
            $scope.image = "";
            $scope.option_1 = "";
            $scope.option_2 = "";
            $scope.option_3 = "";
            $scope.option_4 = "";

        };


        $scope.saveRemix = function () {

            dataService.saveRemix($scope.flix_remix_id,
                                  $scope.movie,
                                  $scope.lessonName,
                                  $scope.selected.flixSubject.id,
                                  $scope.entries);


        }

        $scope.addMore = function () {
            var entry = $scope.getEntry();
            $scope.addEntry(entry);
            $scope.remixEntries = $scope.entries;
            $scope.clearEntryFields();
            $scope.commentary = "";
        };

        $scope.getValue = function (value) {

            var val = "";
            if (!angular.isUndefined(value)) {
                val = value;
            }

            return val;

        }

        $scope.changeTime = function () {
            $scope.time = $scope.timeProgress;
        };

        $scope.editEntry = function (entry) {
            var entryID = $scope.entries.indexOf(entry);

            var time = entry.time;

            //0 0 : 0 0 : 0 0
            //0 1 2 3 4 5 6 7
            var hr = time.substring(0, 2);
            var min = time.substring(3, 5);
            var sec = time.substring(6);


            $scope.timeHR = hr;
            $scope.timeMIN = min;
            $scope.timeSEC = sec;
            $scope.commentary = entry.commentary,
                $scope.link = entry.link;
            $scope.image = entry.image;

            $scope.commentary_type = entry.commentary_type;
            $scope.option_1 = entry.option_1;
            $scope.option_2 = entry.option_2;
            $scope.option_3 = entry.option_3;
            $scope.option_4 = entry.option_4;
            $scope.correct_option = 1;

            $scope.deleteEntry(entry);
        };

        $scope.deleteEntry = function (entry) {
            var entryID = $scope.entries.indexOf(entry);
            $scope.entries.splice(entryID, 1);
            $scope.remixEntries = $scope.entries;
        };

        $scope.loadMovie = function () {
            $scope.lines = new Array(Number($scope.movieRuntime));
        };

        $scope.printIt = function () {
            var table = document.getElementById('printArea').innerHTML;
            var myWindow = $window.open('', '', 'width=800, height=600');
            myWindow.document.write(table);
            myWindow.print();
        };

        // ---------------------> GOOGLE API <---------------------------//

        var imageSearch;

        $scope.addPaginationLinks = function () {

            // To paginate search results, use the cursor function.
            var pages = [];
            var cursor = imageSearch.cursor;
            var curPage = cursor.currentPageIndex; // check what page the app is on
            var pagesDiv = document.createElement('div');
            for (var i = 0; i < cursor.pages.length; i++) {
                var page = cursor.pages[i];
                if (curPage == i) {

                    // If we are on the current page, then don't make a link.
                    var label = document.createTextNode(' ' + page.label + ' ');
                    //pagesDiv.appendChild(label);
                } else {

                    // Create links to other pages using gotoPage() on the searcher.
                    var link = document.createElement('a');
                    link.href = "/image-search/v1/javascript:imageSearch.gotoPage(" + i + ');';
                    link.innerHTML = page.label;
                    link.style.marginRight = '2px';
                    pages.push(link);
                    //pagesDiv.appendChild(link);
                }
            }

            $scope.pages = pages

            //var contentDiv = document.getElementById('content');
            //contentDiv.appendChild(pagesDiv);
        };


        $scope.searchComplete = function () {

            // Check that we got results
            if (imageSearch.results && imageSearch.results.length > 0) {
                $scope.googles = imageSearch.results;
            }
            // Now add links to additional pages of search results.
            $scope.addPaginationLinks(imageSearch);

        }

        $scope.getData = function () {
            imageSearch = new google.search.ImageSearch();
            imageSearch.setSearchCompleteCallback(this, $scope.searchComplete, null);
            imageSearch.execute($scope.googleSearch);
        }


        $scope.loadImage = function (url) {
            $scope.image = url;
        };


        //--------------------> END GOOGLE API <------------------------//

    } ])
    .controller("flixSubmitFlixCntrl", ['$scope', 'dataService', function ($scope, dataService) {

        $scope.entry = "";
        $scope.termsAccepted = false;

        // Events
        $scope.getTopics = function () {
            dataService.getTopics();
        }

        if (dataService.topicListLoaded) {
            $scope.flixSubjects = dataService.topicList;
        } else {
            $scope.getTopics();
        }

        $scope.$on('topicListUpdate', function () {
            $scope.flixSubjects = dataService.topicList;
        });

        /*
        $scope.loadSubjects = function () {
        var url = "/api/flix/get-topics.php";
        $http.get(url).then(function (data) {
        $scope.flixSubjects = data.data.subjects;
        });
        };*/

        $scope.getSelectedSubjects = function () {

            var subjects = "";
            angular.forEach($scope.selectedSubjectNames, function (value, key) {
                subjects = subjects + value.replace(".  ", ""); +";";
            });

            return subjects;

        }

        $scope.btnSubmit_Click = function ($event) {
            if (($scope.entryName == null || $scope.entryName == "") &&
                ($scope.entryLink == null || $scope.entryLink == "")) {
                window.alert("A link or title is required.");
                return;
            }

            var name = $scope.entryName;
            var link = $scope.entryLink;
            var subjects = $scope.getSelectedSubjects();

            //alert("//<?php echo($_SERVER['HTTP_HOST']) ?>/flix/submit-post.php");

            $http.post("submit-post.php", {
                entryName: name,
                entryLink: link,
                entrySubject: subjects
            })
                .success(function (data, status, headers, config) {
                    $scope.entryName = "";
                    $scope.entryLink = "";
                    window.alert("Thank you for submitting a suggestion! " + "We review and add suggestions on a regular basis.");
                }).error(function (data, status, headers, config) {
                    //window.alert("ERROR");
                });

        }

    } ])
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
    .controller("searchBarCtrl", ['$scope', '$rootScope', '$http', 'dataService', function ($scope, $rootScope, $http, dataService) {


        dataService.showMovieList(false);

        $scope.topCategories = [];
        $scope.selectedTopCategory = {
            "id": -1,
            "name": ""
        };
        $scope.vendors = [];
        $scope.selectedVendor = {
            "id": -1,
            "name": ""
        };
        $scope.showListView = true;
        $scope.minimumLength = 0;
        $scope.maximumLength = 600;
        $scope.minimumAge = 18;
        $scope.movieTitleFilter = "";
        $scope.payload = {};
        $scope.showSuggestion = false;
        $scope.flixSubjects = [];
        $scope.suggestionType = "";
        $scope.selectedSubjectNames = [];
        $scope.entryName = "";
        $scope.entryLink = "";

        $scope.getTopics = function () {
            dataService.getTopics();
        }

        if (dataService.topicListLoaded) {
            $scope.topCategories = dataService.topicList;
            $scope.selectedTopCategory = $scope.topCategories[0];
        } else {
            $scope.getTopics();
        }

        $scope.$on('topicListUpdate', function () {
            $scope.topCategories = dataService.topicList;
            $scope.selectedTopCategory = $scope.topCategories[0];
            $scope.flixSubjects = dataService.topicList;
        });

        //dataService.getVendors();
        $scope.$on('vendorListUpdate', function () {
            $scope.vendors = dataService.vendorList;
            $scope.selectedVendor = $scope.vendors[0];
        });


        $scope.setPayload = function () {
            $scope.payload = {
                "selectedCategory": $scope.selectedTopCategory,
                "selectedVendor": $scope.selectedVendor,
                "minimumLength": $scope.minimumLength,
                "maximumLength": $scope.maximumLength,
                "minimumAge": $scope.minimumAge,
                "movieTitleFilter": $scope.movieTitleFilter
            };

            dataService.setPayload($scope.payload);
            dataService.showMovieList(true);

            var activity_type = "SEARCH_FILTER";
            var product_id = -1;
            var topic_id = $scope.selectedTopCategory.id;
            var data = "min_len: " + $scope.minimumLength + " - " +
                       "max_len: " + $scope.maximumLength + " - " +
                       "min_age: " + $scope.minimumAge + " - " +
                       "text_filter: " + $scope.movieTitleFilter;

            dataService.logActivity(activity_type,
                                    product_id,
                                    topic_id,
                                    data);



        }

        //$scope.setPayload();

        $scope.$on('payloadUpdate', function () {
            $scope.payload = dataService.payload;
            $scope.selectedTopCategory = $scope.payload.selectedCategory;
            $scope.selectedVendor = $scope.payload.selectedVendor;
            $scope.minimumLength = $scope.payload.minimumLength;
            $scope.maximumLength = $scope.payload.maximumLength;
            $scope.minimumAge = $scope.payload.minimumAge;
            $scope.movieTitleFilter = $scope.payload.movieTitleFilter;
        });

        $scope.btnMakeSuggestion = function () {
            $scope.showSuggestion = !$scope.showSuggestion;
            dataService.showSuggestionView($scope.showSuggestion);
        }


        $scope.btnShowList = function () {
            $scope.showListView = true;
            dataService.showMovieList(true);
            dataService.showView($scope.showListView);
        }

        $scope.btnShowGrid = function () {
            $scope.showListView = false;
            dataService.showMovieList(true);
            dataService.showView($scope.showListView);
        }

        $scope.getSelectedSubjects = function () {

            var subjects = "";
            angular.forEach($scope.selectedSubjectNames, function (value, key) {
                subjects = subjects + value.replace(".  ", "") + ";";
            });

            return subjects;

        }

        $scope.submitMovieSuggestion = function () {

            dataService.submitMovieSuggestion($scope.entryName, $scope.entryLink, $scope.getSelectedSubjects());

        }

        $scope.submitTopicSuggestion = function () {

            dataService.sendFeedback(-1,
                $scope.suggestionType,
                $scope.suggestion);

            $scope.suggestion = "";

        }

        $scope.submitGeneralFeedback = function () {

            dataService.sendFeedback(-1,
                $scope.suggestionType,
                $scope.suggestion);

            $scope.suggestion = "";

        }

        $scope.btnSuggestionClick = function () {

            if ($scope.suggestionType == 'MOVIE SUGGESTION') {
                $scope.submitMovieSuggestion();
            } else if ($scope.suggestionType == 'TOPIC SUGGESTION') {
                $scope.submitTopicSuggestion();
            } else if ($scope.suggestionType == 'GENERAL FEEDBACK') {
                $scope.submitGeneralFeedback();
            } else {

            }


        }



    } ])
    .controller("prospectCtrl", ['$scope', '$http', function ($scope, $http) {

        $scope.btnSignMeUp = function () {
            if (angular.isUndefined($scope.signMeUpEmail)) {
                alert("invalid email");
            } else if ($scope.signMeUpEmail.length < 1) {
                alert("invalid email");
            } else {

                $http.post("/admin/api/flix_academy_api.php?action=isMember&api_token=flixteam2014&email=" + $scope.signMeUpEmail)
                    .success(function (data, status, headers, config) {
                        var isMember = data.isMember;

                        if (isMember) {
                            alert("It appears you are already a member.  Please sign in to take full advantage of the features on our site.");
                            //$window.open("/account/signin.php");
                        } else {
                            $http.post("/account/add-prospect.php", {
                                email: $scope.signMeUpEmail
                            })
                                .success(function (data, status, headers, config) {
                                    alert("Thank you for signing up.  An email will be send to you soon.");
                                    $scope.signMeUpEmail = "";
                                }).error(function (data, status, headers, config) {

                                });

                        }


                    }).error(function (data, status, headers, config) {

                    });

            }

        }

    } ])
    .controller("mainCtrl", ['$scope', '$state', '$stateParams', '$http', '$window', 'dataService', function ($scope, $state, $stateParams, $http, $window, dataService) {

        alert("angularjs working!");
            
        $scope.showSearchbar = 1;
        var NORMAL_MODE = 1;
        var PLAYLIST_MODE = 2;

        var mode = $stateParams.mode;
        dataService.listMode = mode;

        switch (mode) {
            case NORMAL_MODE:
                break;
            case PLAYLIST_MODE:
                $scope.showSearchbar = 0;
                break;
        };

        $scope.showSuggestion = false;

        $scope.$on('showSuggestion', function () {
            $scope.showSuggestion = dataService.showSuggestion;
        });

    } ])
    .controller("listCtrl", ['$scope', '$http', '$window', '$state', '$location', '$filter', 'dataService', function ($scope, $http, $window, $state, $location, $filter, dataService) {

        $scope.playlist_product_ids = [];
        if (dataService.isUser) {
            dataService.getPlaylist().then(function (data) {
                $scope.playlist_product_ids = data;
                //angular.forEach($scope.playlist_product_ids, function (product, index) {

                //})

            });


        }

        $scope.isSearching = false;
        $scope.selectedTopCategory = {
            "id": -1,
            "name": ""
        };
        $scope.selectedSubCategory = {
            "id": -1,
            "name": ""
        };
        $scope.minimumLength = 0;
        $scope.maximumLength = 600;
        $scope.minimumAge = 18;
        $scope.payload = {};
        $scope.movies = [];
        $scope.showMovieOptions = false;
        $scope.filteredMovies = [];
        $scope.messageShow = true;
        $scope.movieTitleFilter = "";

        //$scope.showMovies = dataService.showMovies;
        $scope.$on('showMoviesUpdate', function () {
            $scope.showMovies = dataService.showMovies;
        });


        //$scope.showListView = true;

        $scope.$on('showListView', function () {
            $scope.showListView = dataService.showListView;
        });

        //use dataService to get profile data
        $scope.isUser = dataService.isUser;
        $scope._profile_id = dataService.getProfileId();

        $scope.$on('movieListUpdate', function () {
            $scope.movies = dataService.movieList;
        });

        $scope.setPayload = function () {

            $scope.payload = dataService.payload;
            $scope.selectedTopCategory = $scope.payload.selectedCategory;
            $scope.selectedSubCategory = $scope.payload.selectedVendor;
            $scope.minimumLength = $scope.payload.minimumLength;
            $scope.maximumLength = $scope.payload.maximumLength;
            $scope.minimumAge = $scope.payload.minimumAge;
            $scope.movieTitleFilter = $scope.payload.movieTitleFilter;
            dataService.setPayload($scope.payload);

        }


        $scope.$on('payloadUpdate', function () {
            $scope.payload = dataService.payload;
            $scope.selectedTopCategory = $scope.payload.selectedCategory;
            $scope.selectedSubCategory = $scope.payload.selectedVendor;
            $scope.minimumLength = $scope.payload.minimumLength;
            $scope.maximumLength = $scope.payload.maximumLength;
            $scope.minimumAge = $scope.payload.minimumAge;
            $scope.movieTitleFilter = $scope.payload.movieTitleFilter;
        });

        $scope.hoverOverMovie = function () {
            $scope.showMovieOptions = true;

        }

        $scope.openRemix = function (movie, remix_id) {
            dataService.openRemix(movie, $scope.selectedTopCategory, remix_id);
        }

        $scope.openMovie = function (movie, remix_id) {
            dataService.openMovie(movie);
        }


        $scope.getMovieImage = function (movie) {

            var imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt.png';

            if (dataService.getDeviceSize() == 'xs') {
                imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt_small.png';
            }

            if (movie.product_image != null && movie.product_image.length > 0) {
                imageUrl = movie.product_image;
            }

            return imageUrl;

        }

        $scope.setMovie = function (movie) {
            dataService.setMovie(movie);
        };

        $scope.setShowMovieOptions = function (show, movie, event) {

            $scope.showMovieOptions = show;


            if (show) {
                //dataService.setMovie(movie);
            }

            dataService.showMovieView(show, event.pageX, event.pageY);


        };

        $scope.getTooltip = function (movie) {

            //dataService.setMovie(movie);
            //dataService.showMovieView(true);
            //$scope.showMovieOptions = true;
            var tooltipMsg = "";

            if (movie.description != null && movie.description.length > 0) {

                var words = movie.description.split(" ");
                var wordCount = 0;
                var sentence = "";
                var maxWords = 20;

                angular.forEach(words, function (word, index) {
                    if (wordCount == 0) {
                        sentence = word;
                        wordCount = wordCount + 1;
                    } else if (wordCount < maxWords) {
                        sentence = sentence + " " + word;
                        wordCount = wordCount + 1;
                    }
                })

                tooltipMsg = sentence + "...";


            } else {
                tooltipMsg = movie.title;
            }

            return tooltipMsg;

        };

        $scope.uriEncode = function (t) {
            return encodeURI(t);
        };

        $scope.getMinAge = function (rating) {
            // alert(rating);
            var agelow = 1;

            switch (rating) {
                case "PG-13":
                    agelow = 13;
                    break;
                case "NC-17":
                    agelow = 18;
                    break;
                case "PG":
                    agelow = 8;
                    break;
                case "G":
                    agelow = 3;
                    break;
                case "R":
                    agelow = 17;
                    break;
                case "NR":
                    agelow = 1;
                    break;
                case "TV-Y":
                    agelow = 3;
                    break;
                case "TV-Y7":
                    agelow = 7;
                    break;
                case "TV-PG":
                    agelow = 8;
                    break;
                case "TV-G":
                    agelow = 3;
                    break;
                case "TV-14":
                    agelow = 14;
                    break;
                default:
                    validrating = true;
                    break;
            }
            //alert(agelow);
            return agelow;
        }

        $scope.openMovie = function (movie) {
            dataService.setMovie(movie);
            $location.path('flix_main_movie');
        };

        $scope.submitRemix = function (movie) {
            dataService.submitRemix(movie, $scope.selectedTopCategory.id, $scope.selectedTopCategory.name);
        }

        $scope.addMovieToPlaylist = function (movie) {
            dataService.addMovieToPlaylist(movie);
        }

        $scope.rateMovie = function (rating, movie) {
            dataService.rateMovie(rating, movie);
        }

        $scope.getProfileID = function () {

            var _profile_id = -99;

            if ($scope.isUser) {

                if ($scope._profile_id < 1) {
                    _profile_id = -99;
                }

            }

            return _profile_id;

        };

        $scope.matchesTopic = function (movie) {
            var match = false;

            angular.forEach(movie.subjects, function (subject) {

                if (!match) {
                    var searchThrough = subject.subject_id;
                    var searchFor = $scope.selectedTopCategory.id;
                    var searchForParent = false;

                    if ($scope.selectedTopCategory.parent_id === 0) {
                        searchForParent = subject.parent_id === $scope.selectedTopCategory.id;
                    }

                    if ((searchThrough === searchFor) || searchForParent) {
                        match = true;
                    }
                }


            });

            return match;
        }

        $scope.matchesAge = function (movie) {
            var match = false;

            if ($scope.minimumAge >= $scope.getMinAge(movie.rating)) {
                match = true;
            }

            return match;
        }

        $scope.matchesLength = function (movie) {
            var match = false;

            if (movie.length >= $scope.minimumLength &&
                movie.length <= $scope.maximumLength) {

                match = true;

            }

            return match;
        }

        $scope.matchesTitle = function (movie) {
            var match = true;

            if ($scope.movieTitleFilter != null && $scope.movieTitleFilter.length > 0) {
                match = false;
                if (movie.title.toLowerCase().indexOf($scope.movieTitleFilter.toLowerCase()) > -1) {
                    match = true;
                }
            }

            return match;
        }

        $scope.needsTopicFilter = function () {
            var needsTopicFilter = false;

            if ($scope.selectedTopCategory.id != undefined &&
                $scope.selectedTopCategory.id > 0) {
                needsTopicFilter = true;
            }

            return needsTopicFilter;
        }

        $scope.matchesPlaylist = function (movie) {
            var match = false;

            angular.forEach($scope.playlist_product_ids, function (product, index) {
                if (!match) {
                    if (movie.product_id == product.product_id) {
                        match = true;
                    }
                }
            })

            return match;
        }

        $scope.filterList = function (movie) {

            var found = false;

            if (dataService.listMode == 1) {
                if ($scope.matchesLength(movie)) {
                    if ($scope.matchesAge(movie)) {
                        if ($scope.matchesTitle(movie)) {
                            if (!$scope.needsTopicFilter()) {
                                found = true;
                            } else {
                                if ($scope.matchesTopic(movie)) {
                                    found = true;
                                }
                            }
                        }
                    }
                }
            } else if (dataService.listMode == 2) {
                if ($scope.matchesPlaylist(movie)) {
                    found = true;
                }
            } else {

            }

            dataService.filteredMovies = $scope.filteredMovies;
            return found;
        };


        $scope.showPlaylist = function (_profile_id) {
            $scope.isSearching = true;
            //$.cookie("search-criteria", null);

            var payload = {
                profile_id: _profile_id
            };

            $http.post("/flix/search-playlist-post.php", payload)
                .success(function (data, status, headers, config) {
                    $scope.movies = data.movies;
                    setTimeout(function () {
                        $('html, body').animate({
                            scrollTop: $("#resultsHeader").offset().top
                        },
                            750,
                            function () {

                            });
                    });
                    $scope.isSearching = false;
                }).error(function (data, status, headers, config) {
                    //<?php displayErrorIfDevelopment() ?>
                    $scope.isSearching = false;
                });
        }

        $scope.showRemixSubmissions = function ($profile_id) {
            $scope.isSearching = true;
            //$.cookie("search-criteria", null);

            var payload = {
                profile_id: $profile_id
            };

            $http.post("/flix/search-remix-post.php", payload)
                .success(function (data, status, headers, config) {
                    $scope.movies = data.movies;
                    setTimeout(function () {
                        $('html, body').animate({
                            scrollTop: $("#resultsHeader").offset().top
                        },
                            750,
                            function () {

                            });
                    });
                    $scope.isSearching = false;
                }).error(function (data, status, headers, config) {
                    //<?php displayErrorIfDevelopment() ?>
                    $scope.isSearching = false;
                });
        }

        if (dataService.filteredMovies.length > 0) {
            if (dataService.payload != null) {
                $scope.setPayload();
            }
            $scope.movies = dataService.movieList;
            dataService.showMovies = true;
            $scope.showMovies = true;
        } else {
            dataService.getMovies();
        }


    } ])
    .controller("movieViewCtrl", ['$scope', '$state', '$stateParams', '$location', '$anchorScroll', 'dataService', function ($scope, $state, $stateParams, $location, $anchorScroll, dataService) {

        var movie = $stateParams.movie;

        if (movie != null) {
            dataService.setMovie(movie);
        } else {
            dataService.setMovie(dataService.movie);
        }

        $scope.feedback_types = [
            { name: 'MOVIE COMMENT', value: 'MOVIE COMMENT' }, 
            { name: 'MOVIE LESSON SUGGESTION', value: 'MOVIE LESSON SUGGESTION' }, 
            { name: 'MOVIE INFO', value: 'MOVIE INFO' }, 
            { name: 'MOVIE LINK', value: 'MOVIE LINK' }, 
            { name: 'MOVIE LINK ISSUE', value: 'MOVIE LINK ISSUE' }, 
            { name: 'GENERAL FEEDBACK', value: 'GENERAL FEEDBACK' }, 
            { name: 'TEACHER COMMENT', value: 'TEACHER COMMENT' }, 
            { name: 'STUDENT COMMENT', value: 'STUDENT COMMENT' }
        ];

        $scope.feedback_type = $scope.feedback_types[0];


        $scope.getMovieImage = function (movie) {

            var imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt.png';

            if (dataService.getDeviceSize() == 'xs') {
                imageUrl = dataService.rootPath + '/public/images/FlixAcademyCoverArt_small.png';
            }

            if (movie.product_image != null && movie.product_image.length > 0) {
                imageUrl = movie.product_image;
            }

            return imageUrl;

        }

        

        $scope.btnSendFeeback = function (feedback) {
            dataService.sendFeedback($scope.movie.product_id,
                $scope.feedback_type.name,
                feedback);


            $scope.showFeedback = false;
            $scope.feedback = "";

        }

        // ---------------------------------------------------------

        $scope.movie = {};
        $scope.movie = dataService.getMovie();
        $scope.feedback = '';
        $scope.showFeedback = false;

        $scope.$on('movieUpdate', function () {
            $scope.movie = dataService.movie;
        });

        $scope.addMovieToPlaylist = function (movie) {
            dataService.addMovieToPlaylist(movie);
        }

        $scope.rateMovie = function (rating, movie) {
            dataService.rateMovie(rating, movie);
        }

        $scope.openRemix = function (movie, remix_id) {
            dataService.openRemix(movie, $scope.selectedTopCategory, remix_id);
        }

    } ])
    


    /*

    .controller("flixMovieViewCtrl", ['$scope', 'dataService', function ($scope, dataService) {

        $scope.movie = {};
        $scope.movie = dataService.getMovie();
        $scope.feedback = '';
        $scope.showFeedback = false;

        $scope.$on('movieUpdate', function () {
            $scope.movie = dataService.movie;
        });


        $scope.submitRemix = function (movie) {
            dataService.submitRemix(movie);
        }

        $scope.addMovieToPlaylist = function (movie) {
            dataService.addMovieToPlaylist(movie);
        }

        $scope.rateMovie = function (rating, movie) {
            dataService.rateMovie(rating, movie);
        }

        $scope.btnSendFeeback = function (feedback) {
            var feedbackPosted = dataService.sendFeedback($scope.movie.product_id,
                $scope.feedbackType,
                feedback);


            $scope.showFeedback = false;
            $scope.feedback = "";

        }

        $scope.openRemix = function (movie) {
            dataService.openRemix(movie, $scope.selectedTopCategory, -1);
        }

    } ])

        // FLIX MOVIE SUBMISSION PAGE =====================================================
        .state('flix_main_submit_movie', {
            url: '/flix_main_submit_movie',
            templateUrl: '/flix/flix_main_submit_movie.html'
        })


        // FLIX MOVIE SUBMISSION PAGE =====================================================
        .state('flix_main_submit_topic', {
            url: '/flix_main_submit_topic',
            templateUrl: '/flix/flix_main_submit_topic.html'
        })


        // FLIX REMIX PAGE ================================================
        .state('flix_remix', {
            url: '/flix_remix_submission',
            templateUrl: '/flix/flix_remix_submission.php',
            controller: ''
        })

    
    .controller("flixMiniMovieViewCtrl", ['$scope', 'dataService', function ($scope, dataService) {

        $scope.showMovie = false;
        $scope.movie = {};
        $scope.position = {};
        //$scope.movie = dataService.getMovie();

        $scope.showView = function (show) {
            dataService.showMovieView(show);
        }

        $scope.$on('movieUpdate', function () {
            //dataService.showMovieView(false);
            //$scope.movie = dataService.movie;
            //dataService.showMovieView(true);
            //$scope.getDescription($scope.movie);
            //$scope.showMovie = dataService.showMovie;
        });

        $scope.$on('showMovieUpdate', function () {
            $scope.movie = dataService.movie;
            $scope.showMovie = dataService.showMovie;
            $scope.position = {
                "x": dataService.x,
                "y": dataService.y
            };
        });

        $scope.openMovie = function () {
            dataService.setMovie($scope.movie);
            //$state.go('flix_main_movie');
            //dataService.showMovieView(true);
        };

        $scope.getDescription = function (movie) {

            var tooltipMsg = "";

            if (movie.description != null && movie.description.length > 0) {

                var words = movie.description.split(" ");
                var wordCount = 0;
                var sentence = "";
                var maxWords = 20;

                angular.forEach(words, function (word, index) {
                    if (wordCount == 0) {
                        sentence = word;
                        wordCount = wordCount + 1;
                    } else if (wordCount < maxWords) {
                        sentence = sentence + " " + word;
                        wordCount = wordCount + 1;
                    }
                })

                tooltipMsg = sentence + "...";


            }

            return tooltipMsg;

        };

    } ])
    
    
    

        $scope.getFlixMovie = function (product_id) {
            var url = "/api/flix/flix_academy_api.php?action=getFlixMovie&api_token=flixteam2014&product_id=" + product_id;

            $http.get(url).then(function (data) {

                //alert(data.data.movies[0].title);

                var _movie = data.data.movies[0];

                var tempMovie = {
                    "Title": _movie.title,
                    "Year": _movie.release_year,
                    "Rated": _movie.rating,
                    "Released": _movie.release_date,
                    "Runtime": _movie.length,
                    "Genre": '',
                    "Director": _movie.director,
                    "Writer": _movie.writer,
                    "Actors": _movie.actors,
                    "Plot": _movie.description,
                    "Language": _movie.language,
                    "Country": _movie.country,
                    "Awards": _movie.awards,
                    "Poster": _movie.product_image,
                    "Metascore": '',
                    "Imdbrating": '',
                    "Imdbvotes": '',
                    "imdbID": _movie.imdb_id,
                    "Type": _movie.type,
                    "isFlix": true,
                    "product_id": _movie.product_id,
                    "SuggestedBy": '',
                    "suggestion_id": -1,
                    "subjects": _movie.subjects,
                    "vendors": _movie.vendors
                };


                $scope.loadMovie(tempMovie);

            });
        };

        //from omdb or flix - may need to tweak
        $scope.loadMovie = function (movie) {

            $scope.movie = movie;

            $scope.movieTitle = movie.Title;
            $scope.movieYear = movie.Year;
            $scope.movieRated = movie.Rated;
            $scope.movieReleased = movie.Released;
            $scope.movieRuntime = movie.Runtime;
            $scope.movieGenre = movie.Genre;
            $scope.movieDirector = movie.Director;
            $scope.movieWriter = movie.Writer;
            $scope.movieActors = movie.Actors;
            $scope.moviePlot = movie.Plot;
            $scope.movieLanguage = movie.Language;
            $scope.movieCountry = movie.Country;
            $scope.movieAwards = movie.Awards;
            $scope.moviePoster = movie.Poster;
            $scope.coverArt = movie.Poster;
            $scope.movieMetascore = movie.Metascore;
            $scope.movieimdbRating = movie.imdbRating;
            $scope.movieImdbvotes = movie.imdbVotes;
            $scope.movieimdbID = movie.imdbID;
            $scope.movieType = movie.Type;
            $scope.coverArtChecked = true;

            $scope.movieSuggestionID = movie.suggestion_id;
            $scope.movieSuggestedBy = movie.SuggestedBy;
            $scope.movieflix_id = movie.product_id;


            //$scope.googleSearch = movie.Title;
            //$scope.getData();
            //$scope.getData();


        }

        //flix movie     
        $scope.$on('handleFlixMovieBroadcast', function () {
            //$scope.getFlixMovie(mMessageService.product_id);
        });

    
    
    */