//http://stackoverflow.com/questions/226663/parse-rss-with-jquery
function parseRSS(url, callback) {
	$.ajax({
		url: (document.location.protocol == "file:" ? "http:" : document.location.protocol) + '//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&callback=?&q=' + encodeURIComponent(url),
		dataType: 'json',
		success: function(data) {
			callback(data.responseData.feed);
		}
	});
}

//https://vverma.net/fetch-any-json-using-jsonp-and-yql.html
//https://developer.yahoo.com/yql/console/?q=select%20*%20from%20json%20where%20url%3D%22http%3A%2F%2Fsearch.twitter.com%2Fsearch.json%3Fq%3Dpuppy%22
//Limit the result-set as large numbers cause null results.
function loadYQL(url, callback) {
	var yql_url = 'https://query.yahooapis.com/v1/public/yql';

	$.ajax({
		'url': yql_url,
		'data': {
			'q': 'SELECT * FROM json WHERE url="'+url+'"',
			'format': 'json',
			'jsonCompat': 'new'
		},
		'dataType': 'jsonp',
		'success': function(response) {
			callback(response);
		},
	});
}

//Gold coast suburbs
function getSuburbs(callback) {
	//https://data.gov.au/dataset/gold-coast-streets-and-suburbs/resource/ad3a8b32-c826-4bc4-ba0a-009d38257ea0
	//http://docs.ckan.org/en/latest/maintaining/datastore.html?highlight=datastore_search#ckanext.datastore.logic.action.datastore_search
	$.ajax({
		url: "http://data.gov.au/api/action/datastore_search",
		dataType: 'json',
		cache: true,
		data: {
			"resource_id": "ad3a8b32-c826-4bc4-ba0a-009d38257ea0",
			"limit": 150,
			"offset": 0,
			"sort": "SUBURB asc",
			"distinct": true,
			"fields": "SUBURB"
		},
		success: function(data) {
			var suburbs = [];
			for(var i = 0 ; i < data.result.records.length ; i++) {
				suburbs.push(data.result.records[i].SUBURB);
			}
			callback(suburbs);
		}
	});
}

//BoM weather warnings
function getWeatherWarnings(callback) {
	parseRSS("http://www.bom.gov.au/fwo/IDZ00056.warnings_qld.xml", function(feed){
		callback(feed.entries);
	});
}

//Road conditions
//There is a "local_government_area" property, but not sure how to query from the base. Using slow suburbs to be standard.
function getRoadConditions(suburbs, callback) {
	$.ajax({
		url: "https://data.qldtraffic.qld.gov.au/events.geojson",
		dataType: 'json',
		success: function(data) {
			callback(data.features.filter(function(obj){
				return $.inArray(obj.properties.road_summary.locality.toUpperCase(), suburbs) > -1
			}));
		}
	});
}

//Emergency power outages
function getPowerOutageEmergency(suburbs, startRank, callback) {
	loadYQL("https://www.energex.com.au/fb-search/test-proxies/search.json-proxy/_nocache?sort=metaL&num_ranks=500&collection=energex-outages-v2&query=!null&start_rank="+startRank, function(data){
		callback(data.query.results.json.response.resultPacket.results.filter(function(obj){return $.inArray(obj.metaData.suburb, suburbs) > -1}));
		if(data.query.results.json.response.resultPacket.resultsSummary.nextStart != null) {
			getPowerOutageEmergency(suburbs, data.query.results.json.response.resultPacket.resultsSummary.nextStart, callback);
		}
	});
}

//Planned power outages
function getPowerOutagePlanned(suburbs, startRank, callback) {
	var results = [];
	loadYQL("https://www.energex.com.au/fb-search/test-proxies/search.json-proxy/_nocache?sort=metaL&num_ranks=100&collection=energex-maintenance-outages-v2&query=!null&start_rank="+startRank, function(data){
		callback(data.query.results.json.response.resultPacket.results.filter(function(obj){return $.inArray(obj.metaData.suburb, suburbs) > -1}));
		if(data.query.results.json.response.resultPacket.resultsSummary.nextStart != null) {
			getPowerOutagePlanned(suburbs, data.query.results.json.response.resultPacket.resultsSummary.nextStart, callback);
		}
	});
}

$(document).ready(function(){
	getSuburbs(function(suburbs){

		getWeatherWarnings(function(warnings){
			$.each(warnings, function(index, value){
				var newDiv = $("<div><a></a></div>");
				newDiv.find('a').text(value.title).attr('href',value.link);
				newDiv.insertAfter('#weather');
			});
		});
		
		getRoadConditions(suburbs, function(conditions){
			$.each(conditions, function(index, value){
				var newDiv = $("<div><a></a></div>");
				newDiv.find('a').text(value.properties.description).attr('href',value.properties.web_link);
				newDiv.insertAfter('#road');
			});
		});
		
		getPowerOutageEmergency(suburbs, 1, function(emergencyOutages){
			$.each(emergencyOutages, function(index, value){
				var newDiv = $("<div></div>");
				newDiv.text(value.title);
				newDiv.insertAfter('#emergencypower');
			});
		});

		getPowerOutagePlanned(suburbs, 1, function(plannedOutages){
			$.each(plannedOutages, function(index, value){
				var newDiv = $("<div></div>");
				newDiv.text(value.title);
				newDiv.insertAfter('#plannedpower');
			});
		});
	});
});