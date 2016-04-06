  var app = angular.module('app', ['ui.router']);
  app.config(['$urlRouterProvider', '$stateProvider', function ($urlRouterProvider, $stateProvider) {


      $urlRouterProvider.otherwise('/home');

      $stateProvider

      // HOME STATES AND NESTED VIEWS ========================================
              .state('home', {
                  url: '/home',
                  templateUrl: 'home.html'
              })

      // FLIX MOVIES PAGE =====================================================
          .state('flix_movies', {
              url: '/flix_movies',
              templateUrl: 'flix_movies.html'
          })

      // DATA MAINTENANCE PAGE ================================================
          .state('data_maintenance', {
              url: '/data_maintenance',
              templateUrl: 'data_maintenance.html'
          })
  } ])
      .factory('movieService', function () {
          var savedMovie = {};

          function set(movie) {
              savedMovie = movie;
          }

          function get() {
              return savedMovie;
          }

          return {
              set: set,
              get: get
          }


      })
      .factory('youtubeService', function ($http, $q) {
          var youtubeVideo = {};
          var apiKey = "AIzaSyAgDYyspIiEz5sFLzRh7WbD_LvygYGoKQU";

          function set(video) {
              youtubeVideo = video;
          }

          function get() {
              return youtubeVideo;
          }

          function getYoutubeData () {

              var url = "https://www.googleapis.com/youtube/v3/videos?part=snippet" +
                                        "&id=" + youtubeVideo.id +
                                        "&key=" + apiKey;

            var deferred = $q.defer();
                $http.get(url)
                    .success(function (data) {

                        var youtube = data.data.items[0];

                        youtubeVideo.title = youtube.snippet.title;
                        youtubeVideo.rating = "N/A";
                        youtubeVideo.release_date = youtube.snippet.publishedAt;
                        youtubeVideo.description = youtube.snippet.description;
                        youtubeVideo.product_image = youtube.snippet.thumbnails.default.url;

                        deferred.resolve(youtubeVideo);
                    })

                return deferred.promise;

          }

          return {
              set: set,
              get: get,
              getYoutubeData: getYoutubeData
          }


      })
      .factory('flixApiService', function ($http, $q) {

          return {
              getFlixMoviesByUrl: function (action) {
                  var deferred = $q.defer();
                  $http.get('api/flix_academy_admin_api.php?action=' + action + '&api_token=flixteam2014')
                      .success(function (data) {
                          deferred.resolve(data);
                      })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              },

              getFlixCustomList: function (where) {
                  var deferred = $q.defer();
                  $http.get('api/flix_academy_admin_api.php?action=getCustomListSimple&api_token=flixteam2014&where=' + where)
                      .success(function (data) {
                          deferred.resolve(data);
                      })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              },
              getFlixMovie: function (product_id) {
                  var deferred = $q.defer();
                  $http.get('api/flix_academy_admin_api.php?action=getFlixMovie&api_token=flixteam2014&product_id=' + product_id)
                      .success(function (data) {
                          deferred.resolve(data);
                      })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              },
              loadTopicsAndSubtopics: function () {
                  var deferred = $q.defer();
                  $http.get('api/flix_academy_admin_api.php?action=getTopicsAndSubtopics&api_token=flixteam2014')
                      .success(function (data) {

                          var sortedList = [];
                          var topics = data.topics;
                          var i = 0;
                          alert("topics.length = " + topics);
                          angular.forEach(topic, function (topics, index) {
                              sortedList[i] = topic;
                              i = i++;
                              var subtopics = topic.SubTopics;
                              angular.forEach(subtopic, function (subtopics, subIndex) {
                                  sortedList[i] = subtopic;
                                  i = i++;
                              });
                          });

                          deferred.resolve(sortedList);
                      })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              },
              getLastProductId: function () {
                  var deferred = $q.defer();
                  $http.get('api/flix_academy_admin_api.php?action=getLastProductId&api_token=flixteam2014')
                      .success(function (data) {
                          deferred.resolve(data);
                      })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              },
              updateSuggestion: function (productsuggestion_id, product_id, approved) {
                  var deferred = $q.defer();
                  $http.post("db/updateSuggestion.php", {
                      productsuggestion_id: productsuggestion_id,
                      product_id: product_id,
                      approved: approved
                  }).success(function (data) {
                      deferred.resolve(data);
                  })
                      .error(function (msg, code) {
                          deferred.reject(msg);
                      });

                  return deferred.promise;
              }
          }
      })
      .service('googleService', ['$http', '$rootScope', '$q', function ($http, $rootScope, $q) {

        var APIKEY = "AIzaSyCaOmo_fJl5F3AyLttAdXgVMQOs8sc8pzM";

        var deferred = $q.defer();

        // Upon loading, the Google APIs JS client automatically invokes this callback.
        this.googleApiClientReady = function () {
            alert("googleApiClientReady");
            gapi.client.setApiKey(APIKEY);
            gapi.auth.init(function () {
                alert("auth");
                window.setTimeout(this.loadAPIClientInterfaces, 1);
            });
        };

        this.test = function () {
            alert(APIKEY);
        };


        this.loadAPIClientInterfaces = function () {
            gapi.client.load('youtube', 'v3', function () {

                var request = gapi.client.youtube.search.list({
                    part: 'snippet',
                    channelId: 'UCqhNRDQE_fqBDBwsvmT8cTg',
                    order: 'date',
                    type: 'video'

                });

                request.execute(function (response) {
                    deferred.resolve(data);

                });
            });

            return deferred.promise;
        };

    } ])
      .controller("homeCtrl", function ($scope, $http) {

          $scope.loadCounts = function () {
              var url = "api/flix_academy_admin_api.php?action=getAdminStats&api_token=flixteam2014";
              $http.get(url).then(function (data) {

                  var adminCounts = data.data.stats[0];

                  $scope.profileCount = adminCounts.ProfileCount;
                  $scope.prospectCount = adminCounts.ProspectCount;
                  $scope.remixCount = adminCounts.RemixCount;
                  $scope.suggestionCount = adminCounts.SuggestionCount;
                  $scope.playlistCount = adminCounts.UserWithPlaylist;
                  $scope.approvedMovieCount = adminCounts.ApprovedMovieCount;
                  $scope.unApprovedMovieCount = adminCounts.UnapprovedMovieCount;


              });
          };

          $scope.loadCounts();

      })
      .controller("flixMovieCtrl", function ($scope, $state, $http, $filter, $window, $q, movieService, flixApiService, youtubeService) {

          $scope.isSearching = false;

          $scope.toggleView = function () {
              $scope.showRight = !$scope.showRight;
              $scope.showLeft = !$scope.showLeft;

              if ($scope.showRight) {
                  $scope.clearMovie();
              }

          }

          $scope.saveImage = function () {

          }

          $scope.getLastProductId = function () {
              flixApiService.getLastProductId()
                  .then(function (data) {
                      var status = data.last_product_id[0];
                      $scope.movie.product_id = status.nextVal;
                  })
          };

          $scope.getMovieImage = function (movie) {

              var url = "/public/images/flix-logo.png";

              if (movie.product_image.length > 0) {
                  url = movie.product_image;
              }

              return url;

          }

          $scope.searchMovies = function () {

              $scope.isSearching = true;

              $scope.showRight = false;
              $scope.showLeft = true;

              if ($scope.selectedFilter == 1) {
                  //search

                  $scope.movies = null;
                  var searchFor = $scope.flixSearch;
                  if (searchFor != null) {
                      if (searchFor.length > 0) {
                          $scope.getFlixCustomList('title LIKE \'%' + searchFor + '%\'');
                      }
                  }
              } else {

                  $scope.movies = null;
                  $scope.filterChanged();
              }

              $scope.isSearching = false;


          };

          $scope.hasValue = function (item) {
              if (item.length > 0) {
                  return item;
              } else {
                  return null;
              }
          };

          $scope.filterChanged = function () {

              var value = $scope.selectedFilter;
              $scope.activeSelection = value;

              if (value == 2) {
                  $scope.getFlixMoviesByUrl('getFlixMoviesSimple');
              } else if (value == 3) {
                  $scope.getFlixMoviesByUrl('getMissingLinkMoviesSimple');
              } else if (value == 4) {
                  $scope.getFlixMoviesByUrl('getMissingSubjectMoviesSimple');
              } else if (value == 5) {
                  $scope.getFlixCustomList('product_image LIKE \'\'');
              } else if (value == 6) {
                  $scope.getFlixCustomList('description LIKE \'\'');
              } else if (value == 7) {
                  $scope.getFlixMoviesByUrl('getSuggestions');
              } else if (value == 8) {
                  $scope.getFlixMoviesByUrl('getExistingSuggestions');
              } else {

              }

          };

          $scope.getFlixMoviesByUrl = function (action) {
              flixApiService.getFlixMoviesByUrl(action)
                  .then(function (data) {
                      $scope.handleMovies(data.movies);
                  })
          };

          $scope.getFlixCustomList = function (where) {
              flixApiService.getFlixCustomList(where)
                  .then(function (data) {
                      $scope.handleMovies(data.movies);
                  })
          };

          $scope.getFlixMovie = function (product_id) {

              if (product_id == -1) {
                  $scope.loadMovie($scope.movie);
              } else if ($scope.activeSelection == 7) {
                  $scope.loadMovie($scope.movie);
              } else {
                  flixApiService.getFlixMovie(product_id)
                      .then(function (data) {
                          var movie = data.movies[0];
                          $scope.loadMovie(movie);
                      })
              }
          };

          $scope.handleMovies = function (movies) {
              $scope.vendorLink = [];
              $scope.isSearching = false;
              $scope.movies = null;
              $scope.movies = movies;
              $scope.scrollUp("#resultsHeader", 750);
          }

          $scope.scrollUp = function (location, height) {
              setTimeout(function () {
                  $('html, body').animate({
                      scrollTop: $(location).offset().top
                  },
                      height,
                      function () {

                      });
              });
          }

          $scope.movieTitleClick = function (movie) {
              $scope.newFlix = false;
              $scope.toggleView();
              if (movie.product_id > 0) {
                  $scope.getFlixMovie(movie.product_id);
              } else {
                  $scope.newFlix = true;
                  movie.release_date = "0000-00-00";
                  $scope.loadMovie(movie);
                  $scope.getLastProductId();
                  if (movie.movie_link.indexOf('youtube') > 0) {
                      //$scope.showYouTube = true;
                      $scope.openYoutube(movie);
                  }
              }
          }

          $scope.openYoutube = function (movie) {

              var url = movie.movie_link;
              var _id = url.substring(url.indexOf('=') + 1);
              youtubeService.set({ id: _id });
              $scope.code = _id;//$scope.getYouTubeId(movie.movie_link);
              $scope.selectTab({
                  title: 'YouTube',
                  url: 'flix_movie_tab_youtube.html'
              });

              $scope.initYoutube();
          }

          $scope.loadMovie = function (movie) {
              $scope.clearMovie();
              $scope.storeMovie(movie);
              $scope.initVendorLinks();
              $scope.initMovieTopics();
          }

          $scope.clearMovie = function () {
              $scope.initTopics();
              $scope.initVendors();
              $scope.vendorLinkUrl = {};
              $scope.movie = {};
              $scope.clearMovieFields();
              $scope.vendorLink = [];
          }

          $scope.clearMovieFields = function(){
              
                $scope.movie.title;
                $scope.movie.rating;
                $scope.release_date ='0000-00-00';
                $scope.release_year = '0000';
                $scope.length = '0';
                $scope.description = '';
                $scope.status_id = '1';
                $scope.age_min = '0';
                $scope.product_image = '';
                $scope.country = '';
                $scope.language = '';
                $scope.actors = '';
                $scope.director = '';
                $scope.writer = '';
                $scope.awards = '';
                $scope.type = '1';
                $scope.imdb_id = '0';

          }

          $scope.saveMovie = function () {
              if ($scope.newFlix) {
                  $scope.saveNewMovie();
              } else {
                  $scope.saveDetails();
              }
          }

          $scope.saveDetails = function () {
              var slength = $scope.movie.length;
              var iLength = 0;
              var length = "";

              if ($scope.isNumeric(slength)) {
                  iLength = slength;
              } else {
                  length = slength.replace(/[^0-9]/g, '');
                  if ($scope.isNumeric(length)) {
                      iLength = length;
                  }
              }

              $http.post("db/updateMovie.php", {
                  product_id: $scope.movie.product_id,
                  title: $scope.movie.title,
                  rating: $scope.movie.rating,
                  release_date: '0000-00-00', //movieReleased, get date format
                  release_year: $scope.movie.release_year,
                  length: iLength,
                  description: $scope.movie.description,
                  status_id: 1,
                  age_min: 0,
                  age_max: 0, // get age
                  product_image: $scope.movie.product_image,
                  country: $scope.movie.country,
                  language: $scope.movie.language,
                  actors: $scope.movie.actors,
                  director: $scope.movie.director,
                  writer: $scope.movie.writer,
                  awards: $scope.movie.awards,
                  type: 1,
                  imdb_id: $scope.movie.imdb_id
              })
                  .success(function (data, status, headers, config) {
                      alert($scope.movie.title + " updated on Flix Academy"); //data.data(0).movieStatus(0).status 
                      $scope.getFlixMovie($scope.movie.product_id);
                      $scope.newFlix = false;
                  }).error(function (data, status, headers, config) {
                      alert("error!");
                  });
          }

          $scope.updateSelectedId = function(id){
              $scope.selectedUpdateId = id;
          }

          $scope.updateSuggestion = function(){

              var suggestion_id = -1;

                if ($scope.isNumeric($scope.movie._id)) {
                    suggestion_id = parseInt($scope.movie._id);
                }


                if($scope.selectedUpdateId<4){
                    if (suggestion_id > -1) {
                        flixApiService.updateSuggestion(
                            suggestion_id,
                            -1,
                            $scope.selectedUpdateId);

                       alert("Updated suggestion");
                    }

                }
                

          }

          $scope.saveNewMovie = function () {

              if ($scope.isNumeric($scope.movie._id)) {
                  suggestion_id = parseInt($scope.movie._id);
              }

              var slength = $scope.movie.length;
              var iLength = 0;
              var length = "";
              var iYear = 0000;

              if ($scope.isNumeric(slength)) {
                  iLength = slength;
              } else {
                  length = slength.replace(/[^0-9]/g, '');
                  if ($scope.isNumeric(length)) {
                      iLength = length;
                  }
              }

              if ($scope.isNumeric($scope.movie.release_year)) {
                  iYear = $scope.movie.release_year;
              } 


              $http.post("db/addMovie.php", {
                  product_id: $scope.movie.product_id,
                  title: $scope.movie.title,
                  rating: $scope.movie.rating,
                  release_date: '0000-00-00', //movieReleased, get date format
                  release_year: iYear,
                  length: iLength,
                  description: $scope.movie.description,
                  status_id: 1,
                  age_min: 0,
                  age_max: 0, // get age
                  product_image: $scope.movie.product_image,
                  country: $scope.movie.country,
                  language: $scope.movie.language,
                  actors: $scope.movie.actors,
                  director: $scope.movie.director,
                  writer: $scope.movie.writer,
                  awards: $scope.movie.awards,
                  type: 1,
                  imdb_id: $scope.movie.imdb_id
              })
                  .success(function (data, status, headers, config) {

                      var newProductID = data;

                      var suggestion_id = -1;

                      if ($scope.isNumeric($scope.movie._id)) {
                          suggestion_id = parseInt($scope.movie._id);
                      }

                      if (suggestion_id > -1) {
                          flixApiService.updateSuggestion(
                              suggestion_id,
                              $scope.movie.product_id,
                              1);
                      }

                      $scope.initMovieGrade();

                      alert($scope.movie.title + " saved to Flix Academy");

                      //$state.reload();
                      $scope.getFlixMovie($scope.movie.product_id);

                  }).error(function (data, status, headers, config) {
                      alert("error!");
                  });

          }

          $scope.initMovieGrade = function () {

              $http.post("/api/flix/addFlixGrading.php", {
                  product_id: $scope.movie.product_id,
                  profile_id: -1,
                  score: 1
              })
                  .success(function (data, status, headers, config) {

                  }).error(function (data, status, headers, config) {

                  });

          };

          $scope.storeMovie = function (movie) {
              movieService.set(movie);
              $scope.movie = movie;
          }

          $scope.loadAllTopics = function () {
              var url = "api/flix_academy_admin_api.php?action=getSubjects&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  $scope.allTopics = data.data.subjects;
              });
          };

          // Topic Maintenance
          $scope.loadTopicsAndSubtopicsxxx = function () {
              var url = "api/flix_academy_admin_api.php?action=getTopicsAndSubtopics&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  $scope.topicsAndSubtopics = data.data.topics;
              });
          };

          // Topic Maintenance
          $scope.loadTopicsAndSubtopics = function () {
              var url = "api/flix_academy_admin_api.php?action=getSubjects&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  $scope.topicsAndSubtopics = data.data.subjects;
              });
          };

          $scope.loadTopicsAndSubtopics222 = function () {
              flixApiService.loadTopicsAndSubtopics()
                  .then(function (data) {
                      $scope.topicsAndSubtopics = data;
                  })
          };

          $scope.loadTopicsAndSubtopics55 = function () {
              flixApiService.loadTopicsAndSubtopics()
                  .then(function (data) {
                      var sortedList = [];
                      var topics = data.topics;
                      var i = 0;
                      alert("i = " + i);
                      angular.forEach(topic, function (topics, index) {
                          sortedList[i] = topic;
                          alert(sortedList[i].subject_name);
                          i = i++;
                          var subtopics = topic.SubTopics;
                          angular.forEach(subtopic, function (subtopics, subIndex) {
                              sortedList[i] = subtopic;
                              i = i++;
                          });
                      });
                      $scope.topicsAndSubtopics = sortedList;
                  })
          };
          // Topic Maintenance
          $scope.loadTopicsAndSubtopics2 = function () {
              var url = "api/flix_academy_admin_api.php?action=getTopicsAndSubtopics&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  var sortedList = [];
                  var topics = data.data.topics;
                  var i = 0;
                  angular.forEach(topic, function (topics, index) {
                      alert("topic = " + topic.subject_name);
                      sortedList[i] = topic;
                      i = i + 1;
                      var subtopics = topic.SubTopics;
                      alert("subtopics.length " + subtopics.length);
                      angular.forEach(subtopic, function (subtopics, subIndex) {
                          sortedList[i] = subtopic;
                          i = i + 1;
                          alert("i = " + i);
                      });
                  });

                  alert(" sortedList  = " + sortedList.length);

                  $scope.topicsAndSubtopics = sortedList;
              });
          };

          // Topic Maintenance
          $scope.initTopics = function () {
              $scope.topics = $scope.topicsAndSubtopics;
          };

          // Vendor Maintenance
          $scope.initVendors = function () {
              $scope.vendors = $scope.allVendors;
          };

          $scope.initVendorLinks = function () {
              var movie = $scope.movie;
              angular.forEach($scope.allVendors, function (vendor, v_index) {
                  angular.forEach(movie.vendors, function (movieVendor, mv_index) {
                      if (movieVendor.vendor_id == vendor.vendor_id) {
                          $scope.vendorLink[v_index] = movieVendor.vendor_product_link;
                      }
                  });
              });
          };

          $scope.initMovieTopics = function () {

              var foundTopic = false;

              angular.forEach($scope.topicsAndSubtopics, function (topic, t_index) {

                  foundTopic = false;

                  angular.forEach($scope.movie.subjects, function (movieTopic, mt_index) {

                      if (!foundTopic) {
                          if (movieTopic.subject_id == topic.subject_id) {
                              $scope.chkTopic[t_index] = true;
                              foundTopic = true;
                          } else {
                              $scope.chkTopic[t_index] = false;
                          }
                      }

                  });
              });


          }

          $scope.setVendorLinkValue = function (vendor, vendorIndex) {
              var movie = $scope.movie;
              angular.forEach(movie.vendors, function (movieVendor, mv_index) {
                  if (movieVendor.vendor_id == vendor.vendor_id) {
                      $scope.vendorLink[vendorIndex] = movieVendor.vendor_product_link;
                  }
              });
          }

          $scope.loadAllVendors = function () {
              var url = "api/flix_academy_admin_api.php?action=getVendors&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  $scope.allVendors = data.data.vendors;
              });
          };

          $scope.deleteVendor = function (vendor, vendorIndex) {
              $http.post("db/removeVendorLink.php", {
                  product_id: $scope.movie.product_id,
                  vendor_id: vendor.vendor_id
              })
                  .success(function (data, status, headers, config) {
                      $scope.getFlixMovie($scope.movie.product_id);
                  }).error(function (data, status, headers, config) {
                      alert("error!");
                  });

          };

          $scope.btnDeleteMovie = function (movie) {

              if (confirm("are you sure you want to delete " + movie.title + " from Flix Academy") == true) {
                  $http.post("db/deleteMovie.php", {
                      product_id: movie.product_id
                  })
                      .success(function (data, status, headers, config) {
                          alert(movie.title + " deleted from Flix Academy"); //data.data(0).movieStatus(0).status                           
                      }).error(function (data, status, headers, config) {
                          alert("error!");
                      });
              } else {
                  alert($scope.movieTitle + " will remain on Flix Academy");
              }
          };

          $scope.getValue = function (value) {

              var val = "";

              if (!angular.isUndefined(value)) {
                  val = value;
              }

              return val;

          }

          $scope.isNumeric = function (num) {
              return !isNaN(num);
          };

          $scope.makeIMDBUrl = function (imdbID) {
              return "http://www.imdb.com/title/" + imdbID;
          };

          $scope.saveVendor = function (movie, vendor, link) {

              if (link.length > 0) {
                  $http.post("db/addVendorLink.php", {
                      product_id: movie.product_id,
                      vendor_id: vendor.vendor_id,
                      vendor_product_link: link,
                      vendor_price: 0,
                      vendor_isFree: 0,
                      use_search: 0
                  })
                      .success(function (data, status, headers, config) {
                          //alert(vendor.vendor_name + " - " + link + " added to " +
                          //movie.title);
                      }).error(function (data, status, headers, config) {
                          alert("error!");
                      });
              }

          };

          $scope.saveVendors = function () {


              if ($scope.newFlix) {

                  alert("Please save item to Flix before adding vendors!");

              } else {

                  var arr = [];
                  var updatedCount = 0;
                  var movie = movieService.get();
                  var isAdding = true;

                  $q.all([

                      angular.forEach($scope.vendorLink, function (link, index) {
                          var vendor = $scope.allVendors[index];
                          if (link.length > 0) {
                              updatedCount = updatedCount + 1;
                              $scope.saveVendor(movie, vendor, link);
                          }

                      })

                  ]).then(function (data) {
                      //$scope.movies = null;
                      alert("Added " + updatedCount + " links to " + movie.title + "!");
                      $scope.getFlixMovie(movie.product_id);
                  });

              }




          }

          $scope.updateTopic = function (topic, value) {

              if ($scope.newFlix) {

                  alert("Please save item to Flix before adding topics!");

              } else {

                  var movie = $scope.movie;
                  if (value) {

                      $http.post("db/addSubject.php", {
                          product_id: movie.product_id,
                          subject_id: topic.subject_id
                      })
                          .success(function (data, status, headers, config) {
                              $scope.getFlixMovie(movie.product_id);
                          }).error(function (data, status, headers, config) {
                              alert("error!");
                          });

                  } else {


                      $http.post("db/removeSubject.php", {
                          product_id: movie.product_id,
                          subject_id: topic.subject_id
                      })
                          .success(function (data, status, headers, config) {
                              $scope.getFlixMovie(movie.product_id);
                          }).error(function (data, status, headers, config) {
                              alert("error!");
                          });

                  }

              }



          }

          $scope.setYouTubeId = function (url) {
              $scope.code = url.substring(url.indexOf('=') + 1);
          }


          $scope.getYoutubeId = function (url) {
              return url.substring(url.indexOf('=') + 1);
          }


          $scope.initMovieVariables = function () {
              $scope.vendorLinkUrl = {};
              $scope.movie = {};
              $scope.vendorLink = [];
              $scope.chkTopic = [];
          }


          //$scope.playlist = videoApi.get();
          $scope.movieIndex = -1;
          $scope.topicsAndSubtopics = {};
          $scope.allTopics = {};
          $scope.allVendors = {};
          $scope.showRight = 0;
          $scope.showLeft = 1;
          $scope.activeSelection = -1;

          $scope.initMovieVariables();
          $scope.loadAllTopics();
          $scope.loadAllVendors();
          $scope.loadTopicsAndSubtopics();
          $scope.youtubeUrl = "https://www.youtube.com/watch?v=EvjiMRphYuo";
          $scope.code = 'oHg5SJYRHA0';
          $scope.showYouTube = false;
          $scope.newFlix = false;
          $scope.youtubeID = 0;
          $scope.selectedUpdateId = 2;
          $scope.updateSuggestionTypes = [{id: 2, Name: 'EXIST'},
                                          {id: 3, Name: 'ERROR'},
                                          {id: 4, Name: 'DELETE'}];

          //TABS
          $scope.tabs = [{
              title: 'Topics',
              url: 'flix_movie_tab_topics.html'
          }, {
              title: 'Vendors',
              url: 'flix_movie_tab_vendors.html'
          }, {
              title: 'Google',
              url: 'flix_movie_tab_google_images.html'
          }, {
              title: 'Amazon',
              url: 'flix_movie_tab_amazon.html'
          }, {
              title: 'OMDB',
              url: 'flix_movie_tab_omdb.html'
          }, {
              title: 'YouTube',
              url: 'flix_movie_tab_youtube.html'
          }];


          $scope.currentTab = 'flix_movie_tab_topics.html';


          $scope.selectTab = function (tab) {
              $scope.currentTab = tab.url;

              if (tab.url == 'flix_movie_tab_google_images.html') {
                  $scope.getGoogleImages();
              }
          }

          $scope.isSelected = function (tabUrl) {
              return $scope.currentTab === tabUrl;
          }

          $scope.showRight = 0;
          $scope.showLeft = 1;


          //YOUTUBE
          $scope.apiKey = "AIzaSyAgDYyspIiEz5sFLzRh7WbD_LvygYGoKQU";

          $scope.initYoutube = function(){
              $scope.clearMovieData();
              $scope.getYoutubeData();
          }

          $scope.getYoutubeDataService = function () {
              youtubeService.getYoutubeData()
                  .then(function (data) {
                      var youtube = data[0];
                      $scope.setMovieDataFromYoutube(youtube);
                  })
          };


          $scope.getYoutubeData = function () {
              var id = youtubeService.get().id;
              var url = "https://www.googleapis.com/youtube/v3/videos?part=snippet" +
                                        "&id=" + id +
                                        //"&fields=id,snippet/title,snippet/position,snippet/publishedAt,snippet/thumbnails,snippet/description"
                                        "&key=" + $scope.apiKey;
              $http.get(url).then(function (data) {
                  //$scope.youtubes = data.data.items;
                  var youtube = data.data.items[0];
                  $scope.setMovieDataFromYoutube(youtube);
              });
          }


          $scope.setMovieDataFromYoutube = function (youtube) {

                  $scope.movie.title = youtube.snippet.title;
                  $scope.movie.rating = "N/A";
                  $scope.movie.release_date = youtube.snippet.publishedAt;
                  $scope.movie.description = youtube.snippet.description;
                  $scope.movie.product_image = youtube.snippet.thumbnails.default.url;
                  /*
                  country: $scope.movie.country,
                  language: $scope.movie.language,
                  actors: $scope.movie.actors,
                  director: $scope.movie.director,
                  writer: $scope.movie.writer,
                  awards: $scope.movie.awards,
                  type: 1,
                  imdb_id: $scope.movie.imdb_id
                  */

          }

          $scope.clearMovieData = function () {

                  $scope.movie.title = "";
                  $scope.movie.rating = "";
                  $scope.movie.release_date = "0000-00-00";
                  $scope.movie.description = "";
                  $scope.movie.product_image = "";
                  $scope.movie.country = "";
                  $scope.movie.language = "";
                  $scope.movie.actors = "";
                  $scope.movie.director = "";
                  $scope.movie.writer = "";
                  $scope.movie.awards = "";
                  $scope.movie.imdb_id = "";

          }

      })
      .controller("dataMaintenanceCtrl", function ($scope, $http) {


          $scope.getNewTopicObj = function () {

              var newTopic = {
                  subject_id: 0,
                  subject_name: "NEW PARENT TOPIC",
                  type: 0,
                  parent_id: 0,
                  SubTopics: null
              };

              return newTopic;

          }

          $scope.$on('$stateChangeSuccess', function () {
              $scope.loadSubjects();
          });

          // Topic Maintenance
          $scope.loadSubjects = function () {
              var url = "api/flix_academy_admin_api.php?action=getTopicsAndSubtopics&api_token=flixteam2014";
              $http.get(url).then(function (data) {
                  $scope.flixSubjects = data.data.topics;
                  $scope.flixSubjects.splice(0, 0, $scope.getNewTopicObj());
              });
          };

          $scope.test = function () {
              alert("" + $scope.selectedParentID);
              alert($scope.toProperCase($scope.newTopic));
          }

          $scope.btnSaveTopic = function () {

              var subject_name = $scope.toProperCase($scope.newTopic);
              var type = 1;
              var parent_id = $scope.selectedParentID;

              if (parent_id == 0) {
                  type = 0;
              }

              var url = "api/flix_academy_admin_api.php?" +
                  "action=insertNewTopic" +
                  "&api_token=flixteam2014" +
                  "&subject_name=" + subject_name +
                  "&type=" + type +
                  "&parent_id=" + parent_id;

              alert(url);

              $http.get(url).then(function (data) {
                  var response = data.data.newSubjectEntry;
                  var topic = data.data.subject_name;
                  alert(response + " --> " + topic + " added");
                  $scope.newTopic = "";
                  $scope.loadSubjects();
              });
          };


          $scope.toProperCase = function (input) {
              var words = input.split(' ');
              var results = [];
              for (var i = 0; i < words.length; i++) {
                  var letter = words[i].charAt(0).toUpperCase();
                  results.push(letter + words[i].slice(1));
              }
              return results.join(' ');
          };

          $scope.prepTopic = function (topic) {
              var _topic = topic.subject_name;
              if (topic.type == 0) {
                  _topic = _topic.toUpperCase();
              }
              return _topic;
          };


          $scope.loadAllTopics2 = function () {
              var url = "/api/flix/get-topics.php";
              $http.get(url).then(function (data) {
                  $scope.flixTopics = data.data.subjects;
              });
          };


      })
      .controller("imageCtrl", function ($scope, $http, movieService) {

          var imageSearch;

          $scope.addPaginationLinks = function () {

              // To paginate search results, use the cursor function.
              var cursor = imageSearch.cursor;
              var curPage = cursor.currentPageIndex; // check what page the app is on
              var pagesDiv = document.createElement('div');
              for (var i = 0; i < cursor.pages.length; i++) {
                  var page = cursor.pages[i];
                  if (curPage == i) {

                      // If we are on the current page, then don't make a link.
                      var label = document.createTextNode(' ' + page.label + ' ');
                      pagesDiv.appendChild(label);
                  } else {

                      // Create links to other pages using gotoPage() on the searcher.
                      var link = document.createElement('a');
                      link.href = "/image-search/v1/javascript:imageSearch.gotoPage(" + i + ');';
                      link.innerHTML = page.label;
                      link.style.marginRight = '2px';
                      pagesDiv.appendChild(link);
                  }
              }

              var contentDiv = document.getElementById('content');
              contentDiv.appendChild(pagesDiv);
          };

          $scope.addPaginationLinks2 = function (imageSearch) {
              var cursor = imageSearch.cursor;
              $scope.pages = imageSearch.cursor.pages;
          };

          $scope.searchComplete = function () {

              if (imageSearch.results && imageSearch.results.length > 0) {
                  $scope.images = imageSearch.results;
                  $scope.pages = imageSearch.cursor.pages;
              }

          }

          $scope.getGoogleImages = function () {
              imageSearch = new google.search.ImageSearch();
              imageSearch.setSearchCompleteCallback(this, $scope.searchComplete, null);
              imageSearch.setResultSetSize(8);
              imageSearch.execute(movieService.get().title + "+cover+art");
          }

          $scope.sendImage = function (url) {
              $scope.movie.product_image = url;
          }

      })
      .controller("amazonCtrl", function ($scope, $http, movieService) {

          $scope.getAmazonData = function () {
              var url = "api/search_api.php?action=getAmazonData&search=" + $scope.movie.title.replace(" ", "%20");
              $http.get(url).then(function (data) {
                  $scope.amazons = data.data.results;
              });
          };

          $scope.getAmazonCustomData = function (search) {
              var url = "api/search_api.php?action=getAmazonData&search=" + search.replace(" ", "%20");
              $http.get(url).then(function (data) {
                  $scope.amazons = data.data.results;
              });
          };

          $scope.linkAmazon = function (amazon_url, vendorId) {

              var movie = $scope.movie;

              $http.post("db/addVendorLink.php", {
                  product_id: movie.product_id,
                  vendor_id: vendorId,
                  vendor_product_link: amazon_url,
                  vendor_price: 0,
                  vendor_isFree: 0,
                  use_search: 0
              })
        .success(function (data, status, headers, config) {
            alert("Amazon url linked to movie " + amazon_url + " using vendor id# " + vendorId);
        }).error(function (data, status, headers, config) {
            alert("error!");
        });
          };


          $scope.sendImage = function (imageUrl) {
              $scope.movie.product_image = imageUrl;
          };

          $scope.sendContent = function (editorialreview) {
              $scope.movie.description = editorialreview;
          };

          $scope.sendActors = function (actors) {
              $scope.movie.actors = actors;
          };

          $scope.sendDirectors = function (director) {
              $scope.movie.director = director;
          };

          $scope.sendRating = function (rating) {
              $scope.movie.rating = rating;
          };

      })
      .controller("youtubeCtrl", function ($scope, $http, $window, $rootScope, movieService, youtubeService) {

          

      })
      .controller("omdb_searchCtrl", function ($scope, $http, $q, $timeout, movieService) {

          $scope.getMovie = function (imdbID) {
              var url = "http://www.omdbapi.com/?&y=&plot=full&r=json&tomatoes=true&i=" + imdbID;
              $http.get(url).then(function (data) {
                  return data;
              });
          };

          $scope.sendYear = function (data) {
              $scope.movie.release_year = data;
          }

          $scope.sendReleased = function (data) {
              $scope.movie.release_date = data;
          }

          $scope.sendRating = function (data) {
              $scope.movie.rating = data;
          }

          $scope.sendRuntime = function (data) {
              $scope.movie.length = data;
          }

          $scope.sendGenre = function (data) {
              $scope.movie.genre = data;
          }

          $scope.sendDirector = function (data) {
              $scope.movie.director = data;
          }

          $scope.sendWriter = function (data) {
              $scope.movie.writer = data;
          }

          $scope.sendActors = function (data) {
              $scope.movie.actors = data;
          }

          $scope.sendLanguage = function (data) {
              $scope.movie.language = data;
          }

          $scope.sendCountry = function (data) {
              $scope.movie.country = data;
          }

          $scope.sendPlot = function (data) {
              $scope.movie.description = data;
          }

          $scope.sendAwards = function (data) {
              $scope.movie.awards = data;
          }

          $scope.select = function (movie) {
              $scope.movie = null;
              $scope.movie = movie;
          };

          $scope.loadMovies = function (movies) {
              $scope.omdbs = movies;
          }

          $scope.searchCustom = function () {
              $scope.searchObmd($scope.search);
          }

          $scope.searchMovie = function () {
              $scope.searchObmd($scope.movie.title);
          }

          $scope.searchObmd = function (searchFor) {

              var defer = $q.defer();
              var promises = [];
              var movies = [];
              var url = "http://www.omdbapi.com/?&y=&plot=full&r=json&s=" + searchFor.replace(" ", "%20");

              function lastTask() {
                  $scope.loadMovies(movies).then(function () {
                      defer.resolve();
                  });
              }

              $http.get(url).then(function (data) {
                  angular.forEach(data.data.Search, function (movie) {
                      var _url = "http://www.omdbapi.com/?&y=&plot=full&r=json&tomatoes=true&i=" + movie.imdbID;
                      $http.get(_url).then(function (_data) {
                          var obj = angular.fromJson(_data.data);
                          promises.push(movies.push(obj));
                      });
                  });
              });

              $q.all(promises).then(lastTask);

              return defer;
          }

      })
      .directive('myYoutube', function ($sce) {
          return {
              restrict: 'EA',
              scope: {
                  code: '='
              },
              replace: true,
              template: '<div style="height:400px;"><iframe style="overflow:hidden;height:100%;width:100%" width="100%" height="100%" src="{{url}}" frameborder="0" allowfullscreen></iframe></div>',
              link: function (scope) {
                  scope.$watch('code', function (newVal) {
                      if (newVal) {
                          scope.url = $sce.trustAsResourceUrl("http://www.youtube.com/embed/" + newVal);
                      }
                  });
              }
          };
      });