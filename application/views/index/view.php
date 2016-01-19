      
      <div class="col-md-12">
      	<div class="row">
            <h1>Просмотр с картой</h1>
        </div>
      </div>
      
      <style type="text/css">
            @import url('http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css');

			#map {
			    position:absolute;
			    width: 100%;
			    height: 300px;
			    margin:0;
			    padding:0; 
			    border: 1px solid #E5E5E5;
			    border-radius: 8px;  
			}
			
			#mapRow {
			  height: 300px;
			}
      </style>
      
        <div class="col-sm-9">
          <div class="row" id="mapRow">
            <div class="col-sm-12" id="map">
            </div>
          </div>         
        </div>
      
        <div class="col-sm-3">
          <form class="form-add">
	        <input type="hidden" id="inputLat" name="lat" required />
	        <input type="hidden" id="inputLon" name="lon" required />
		        
		        <h2 class="form-add-heading">Добавить точку</h2>
		        <label for="inputDescription" class="sr-only">Описание точки</label>
		        <input type="text" name="description" id="inputDescription" class="form-control" placeholder="Описание точки" required autofocus>
		        <label for="descCoord">Координаты: <input type="text" id="coords" placeholder="Вида 'lng,lat'"></input></label> <br/>
		        <label for="descAddr">Адрес: <span id="addr"></span></label>
		        <button class="btn btn-lg btn-primary btn-block" type="submit">Добавить</button>
	      </form>
        </div>
     

        
        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
       
        
        <script type='text/javascript'>

	     	// здесь будух храниться ссылки на маркеры на карте
			var points = {};
	    	
	        var myMap = L.map('map', {scrollWheelZoom: true, zoomControl: true});
	
	      	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
					maxZoom: 18,
					attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
						'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
						'Imagery © <a href="http://mapbox.com">Mapbox</a>',
					id: 'mapbox.streets'
		      	}).addTo(myMap);
	
		  	myMap.setView(new L.LatLng(55.74112, 52.40427), 12);
	
		  	myMap.addControl(L.control.zoom({position: 'bottomleft'}));

		  	var popup = L.popup();
	
		  	// ссылка на форму
			var form = $('.form-add');
	
		  	// обработчик формы
			form.on("submit", function() {
				form_submit(this, myMap);
				return false;
			});
		  	
			// обработчик клика по карте
			function onMapClick(e) {
	
				// сокращаем адрес
				var lat = e.latlng.lat.toFixed(5);
				var lon = e.latlng.lng.toFixed(5);
	
				// чтобы не забыть куда кликал пказываем на месте клика балун
				popup
					.setLatLng(e.latlng)
					.setContent("Вы выбрали эту точку")
					.openOn(myMap);
	
				// показываем в удобочитаемом виде
				form.find('#coords').val(lon + ',' + lat);
				// кидаем в инпуты на форме
				form.find('#inputLat').val(lat);
				form.find('#inputLon').val(lon);
	
				form.find('#coords').trigger("change");
				
			}

			function is_valid_coord(coord_string) {
				// проверяем соответствие формату через регулярку
				var regexp = /^-?\d{1,3}\.?\d{0,10},-?\d{1,3}\.?\d{0,10}$/;
				return regexp.test(coord_string);
			}
			
			$(document).ready(function() {
				// ставим обратчик изменения координат
				$('#coords').on('change', function() {

					var val = $(this).val();

					if (is_valid_coord(val)) {
						var coords = val.split(',');
						// сразу меняем данные формы
						form.find('#inputLat').val(coords[1]);
						form.find('#inputLon').val(coords[0]);
						// запрашиваем адрес координаты через яндекс
						$.ajax({type: "GET",
					        url: "http://geocode-maps.yandex.ru/1.x/", 
					        data:'geocode='+val+'&format=json&kind=house&results=1', 
					        dataType:"JSON", timeout:30000, async:true,
					        beforeSend: function () {
					        	form.find('#addr').html("Пытаемся узнать адрес...");
						    },
					        error: function(xhr) {
					            form.find('#addr').html('Ошибка геокодирования: '+xhr.status+' '+xhr.statusText);
					        },
					        success: function(html) { 
					            res=html;
					            var geores=res.response.GeoObjectCollection.featureMember;
					            if(geores.length>0) { 
					            	form.find('#addr').html(geores[0].GeoObject.description + ', ' + geores[0].GeoObject.name);
					            } else {
					            	form.find('#addr').html('Не удалось определить адрес');
					            }                                    
					       }
					   });
					} else {
						form.find('#addr').html('Неправильны формат координат!');
					}
					
					
					   
				});
			});
			
			// обертка для показа сообщений пользователю
			function show_message(msg) {
				alert(msg);
				return true;
			}
	
			// добавление точки
			function add_point(map, id, lat, lon, description) {
	
				var element_id = 'delete-button-'+id;
	
				var marker = new L.Marker([lat, lon]);
				map.addLayer(marker);
	
				// да-да, вот такой хак для кнопки удаления
				marker.bindPopup(description+'<button id="'+element_id+'" onclick="delete_point('+id+'); return false;">Удалить</button>');

				// вешаем событие, чтобы при наведении вылезал попап
				marker.on('mouseover', function (e) {
	                //Popup
	                this.openPopup();
	            });

// 				marker.on('mouseout', function (e) {
// 	                //Popup
// 	                this.closePopup()
// 	            });
				
				// сохраняем ссылку на маркер, чтобы в случае чего его можно было менять или удалить
				points[id] = marker;	
	
				return true;
				
			}
	
			// удаление точки
			function delete_point(id) {
				var marker = points[id];
				if (marker !== undefined) {

					$.ajax({
						url: '/index.php/ajax/delete_point/'+id,
						method: "GET",
						dataType:"JSON", 
						timeout:30000, 
						async:true,
						success: function (response) {
							if (response.success) {
								myMap.removeLayer(marker);
								delete points[id];
								console.log(id);
								show_message(response.message);
							} else {
								show_message('Не удалось удалить точку!');
							}
						},
					});
					
					
				}
				return false;
			}
	
			// открыть popup точки
			function open_point(id) {
				var marker = points[id];
				if (marker !== undefined) {
					marker.openPopup();
				}
			}
			
	        // обработчик формы
			function form_submit(form, map) {
	
				var error = false;
				var data = {};

				if (!is_valid_coord($('#coords').val())) {
					show_message('Эээ... Нет! Это не правильные координаты! Исправь ;)');
					return;
				}
				
				$.each({
					'inputLat'			: 'lat',
					'inputLon'			: 'lon',
					'inputDescription'	: 'description', 
				}, function (k, v){
					var val = $(form).find('#'+k).val();
					if (val == undefined || val == '') {
						error = true;
					} else {
						data[v] = val;
					}
				});
	
				if (!error) {

					$.ajax({
						url: '/index.php/ajax/create_point',
						method: "POST",
						data: data,
						dataType:"JSON", 
						timeout:30000,
						success: function (response) {
							// если успешно сохранили, то кидаем маркер на карту
							if (response.success) {
								var point_id = response.id;
								// добавляем маркер на карту
								add_point(myMap, point_id, data.lat, data.lon, data.description);
								// открываем попап, чтобы фокус перешел на него
								open_point(point_id);
								// очищаем форму
								form_reset(form);
								// уведомление для пользователя
// 								show_message(response.message);
							} else {
								show_message("Что-то пошло не так... Не удалось сохранить точку. Обратитесь к администратору.");
							}
							
						},
						failure: function () {
							show_message("Не удалось сохранить точку! Обратитесь к администратору.");
						}
					});
					
					var id = Math.random(0, 1000);
					
				} else {
					show_message('Ошибка: Необходимо заполнить все поля и должна быть выбрана точка на карте!');
				}
				
			}
	
	        // очистка формы
			function form_reset(form) {
				$.each({
					'inputLat'			: 'lat',
					'inputLon'			: 'lon',
					'inputDescription'	: 'description', 
				}, function (k, v){
					$(form).find('#'+k).val('');
				});
			}
			
			
			// присваиваем обработчик нажатия по карте
			myMap.on('click', onMapClick);

			$.ajax('/index.php/ajax/get_points', {
				method: "GET",
				success: function (response) {
					$.each(response.points, function (k, point) {
						add_point(myMap, point.id, point.lat, point.lon, point.description);
					});
				}
			});
			
        </script>
 