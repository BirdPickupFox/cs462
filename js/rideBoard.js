// Begin Globals
var viewNames = ["agendaDay", "agendaWeek", "month"]; 	// List of calendar views
var currentView = "agendaWeek";				// Current calendar view

var googleMap = null;		// Google Maps map object
var directionsDisplay = null; 	// Google Maps display object
var directionsService = new google.maps.DirectionsService(); // Google Maps direction service object
var currentRoute = null; 	// Google Maps most recently returned route object

var vehicleTable = null; 	// Datatable table for vehicles
// End Globals

/*
 * Opens dialog box for user to sign in
 */
function signIn()
{
	$("#registrationEditor").dialog({
                draggable:true,
                title: "Sign In",
                height: 225,
                width: 315,
                modal: true,
                resizable: false,
                open: function() {
			$("#regEmail").val("");
			$("#regPwd").val("");
                },
                close: function() {
                },
                buttons:
                [
                        {
                                text: "Log In",
                                id: "signInBtn",
                                click: function(){
                                       checkCredentials();
                                },
                        },
                ],
        });
}

/*
 * Checks user credentials from Sign In editor
 */
function checkCredentials()
{
	var email = $("#regEmail").val();
	var password = $("#regPwd").val();

	$("#pageForm").append("<input type=hidden name='formEmail' id='formEmail'>");
	$("#pageForm").append("<input type=hidden name='formPassword' id='formPassword'>");

	$("#formAction").val("@SIGN_IN");
	$("#formEmail").val(email);
	$("#formPassword").val(password);

	$("#pageForm").submit();
}

/*
 * Signs user out and refreshes page
 */
function signOut()
{
	$("#formAction").val("@SIGN_OUT");
	$("#pageForm").submit();
}

/*
 * Opens dialog box for user to register
 */
function register()
{
	$("#registrationEditor").dialog({
		draggable:true,
		title: "Register",
		height: 225,
		width: 315,
		modal: true,
		resizable: false,
		open: function() {
			$("#regEmail").val("");
			$("#regPwd").val("");
		},
		close: function() {
		},
		buttons:
		[
			{
				text: "Create Account",
				id: "createAccountBtn",
				click: function() {
					createAccount();
				},
			},
		],
	});
}

/*
 * Creates account using data from registration editor
 */
function createAccount()
{
	var email = $("#regEmail").val();
	var password = $("#regPwd").val();

	$("#pageForm").append("<input type=hidden name='formEmail' id='formEmail'>");
	$("#pageForm").append("<input type=hidden name='formPassword' id='formPassword'>");

	$("#formAction").val("@CREATE_USER");
	$("#formEmail").val(email);
	$("#formPassword").val(password);

	$("#pageForm").submit();
}

/*
 * Validates new trip data from Create New Trip editor
 */
function isValidTrip()
{
	var vehicleId = $("#vehicleSelect").val();
	var startDate = $("#startDate").val();
	var endDate = $("#endDate").val();
	var price = $("#totalPrice").val();

	var errorStr = "";
	if(vehicleId == -1)
		errorStr += "You must select a vehicle (if you need to register a vehicle, see My Vehicles tab).<br>";
	if(startDate.length != 10)
		errorStr += "You must select a valid departure date (form dd/mm/yyyy).<br>";
	if(endDate.length != 10)
		errorStr += "You must select a valid arrival date (form dd/mm/yyyy).<br>";
	if(currentRoute == null)
		errorStr += "You must select a valid route.<br>";
	if(isNaN(price))
		errorStr += "You must set a numeric price.<br>";
	
	if(errorStr == "")
		return true;
	myAlert(errorStr);
	return false;
}

/*
 * Validates vehicle data from Vehicle editor
 */
function isValidVehicle()
{
	var make = $("#vehicleMake").val();
	var model = $("#vehicleModel").val();
	var year = $("#vehicleYear").val();
	var seatCount = $("#vehicleSeatCount").val();

	var errorStr = "";
	if(make.length == 0)
		errorStr += "You must enter a vehicle make.<br>";
	if(model.length == 0)
		errorStr += "You must enter a vehicle model.<br>";
	if(year.length != 4 || isNaN(year))
		errorStr += "You must enter a 4-digit vehicle year.<br>";
	if(isNaN(seatCount))
		errorStr += "You must enter a numerical seat count.<br>";
	else if(seatCount < 2)
		errorStr += "Seat count must be at least 2.<br>";

	if(errorStr == "")
		return true;
	myAlert(errorStr);
	return false;
}

/*
 * Creates a new trip using data from Create New Trip editor
 */
function createTrip()
{
	var vehicleId = $("#vehicleSelect").val();
	var startDate = new Date($("#startDate").val() + " " + 
					$("#startTimeHour").val() + ":" +
					$("#startTimeMinute").val() + " " +
					$("#startTimePeriod").val());
	var endDate = new Date($("#endDate").val() + " " +
					$("#endTimeHour").val() + ":" +
					$("#endTimeMinute").val() + " " +
					$("#endTimePeriod").val());
	var price = $("#totalPrice").val();

	var origin = currentRoute['routes'][0]['legs'][0]['start_address'];
	var destination = currentRoute['routes'][0]['legs'][0]['end_address'];

	$.ajax({
		dataType: "json",
		type: "POST",
		url: "controller/createTrip.php",
		data: {
			vehicleId: vehicleId,
			startDateTime: startDate.toLocaleString(),
			endDateTime: endDate.toLocaleString(),
			origin: origin,
			destination: destination,
			price: price,
		},
		success: function(response)
		{
			myAlert("TODO - respond to create trip");
		},
		error: function(response)
		{
			myAlert("Error saving trip: " + response.responseText);
		},
	});
}

/*
 * Creates a vehicle using data from Vehicle editor
 */
function createVehicle()
{
	var make = $("#vehicleMake").val();
	var model = $("#vehicleModel").val();
	var year = $("#vehicleYear").val();
	var seatCount = $("#vehicleSeatCount").val();
	var description = $("#vehicleDescription").val();

	$.ajax({
		dataType: "json",
		type: "POST",
		url: "controller/createVehicle.php",
		data: {
			make: make,
			model: model,
			year: year,
			seatCount: seatCount,
			description: description,
		},
		success: function(response)
		{
			myAlert("TODO - respond to create vehicle status: " + response.statusCode);
		},
		error: function(response)
		{
			myAlert("Error saving vehicle: " + response.responseText);
		},
	});
}

/*
 * Navigates to the Trips page
 */
function showTrips()
{
	selectNavigation("tripNav");
	$("#dynamicContent").html($("#tripPage").html());
	initTripCalendar();
	changeCalendarView("agendaWeek");
}

/*
 * Navigates to the My Vehicles page
 */
function showMyVehicles()
{
	selectNavigation("vehicleNav");
	$("#dynamicContent").html($("#vehiclePage").html());
	initVehicleTable();
}

/*
 * Navigates to the My Notifications page
 */
function showMyNotifications()
{
	selectNavigation("notifyNav");
	$("#dynamicContent").html($("#notifyPage").html());
}

/*
 * Deselects all navigation links, then selects the link
 * with the given id
 *
 * @param id - HTML id of link to highlight
 */
function selectNavigation(id)
{
	$(".navLink").removeClass('selected');
	$("#" + id).addClass('selected');
}

/*
 * Initializes trip calendar
 */
function initTripCalendar()
{
	$("#tripCalendar").fullCalendar({
		defaultView: currentView,
		weekends: true,
		selectable: true,
		unselectAuto: true,
		selectHelper: true,
		events: function(start, end, callback)
		{
			// TODO load trips
		},
		eventClick: function(calEvent)
		{
			// TODO open trip editor (for existing)
		},
		select: function(startDate, endDate)
		{
			openNewTripEditor(startDate, endDate);
		},
		viewDisplay: function(view)
		{
			// TODO react to change in view (probably not needed)
		},
		eventDrop: function(evt, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view)
		{
			// TODO update trip (reflect in Google Calendar)
		},
		eventResize: function(evt, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view)
		{
			// TODO update trip (reflect in Google Calendar)
		},
		header: {
			left: '',
			center: 'prev title next ',
			right: '',
		},
		editable: true,
		theme: true,
		minTime: 0,
		maxTime: 24,
		allDaySlot: false,
		aspectRatio: 1.55,
		columnFormat: {
			month: 'dddd',
			week: 'dddd M/d',
			day: 'dddd M/d',
		},
	});
}

/*
 * Initializes vehicle table
 */
function initVehicleTable()
{
	vehicleTable = $("#vehicleTable").dataTable({
		"aoColumnDefs": [
			{"bSortable": false, "sWidth": "5%", "aTargets":[0]}, // ID
			{"bSortable": false, "sWidth": "15%", "aTargets":[1]}, // Make
			{"bSortable": false, "sWidth": "15%", "aTargets":[2]}, // Model
			{"bSortable": false, "sWidth": "10%", "aTargets":[3]}, // Year
			{"bSortable": false, "sWidth": "15%", "aTargets":[4]}, // Seat Count
			{"bSortable": false, "sWidth": "40%", "aTargets":[5]}, // Description
		],
		"bAutoWidth": false,
		"bLengthChange": false,
		"bPaginate": false,
		"bProcessing": false,
		"bServerSide": true,
		"oLanguage": {
			"sEmptyTable": "You have no registered vehicles",
		},
		"sAjaxSource": "controller/getMyVehicles.php",
		"sDom": "<t>",
		"fnServerData": function(sSource, aoData, fnCallback, oSettings)
		{
			oSettings.jqXHR = $.ajax({
				dataType: "json",
				type: "POST",
				url: sSource,
				data: aoData,
				success: function(response)
				{
					fnCallback(response);
				},
				error: function(response)
				{
					myAlert("Error loading vehicle table: " + response.responseText);
				},
			});
		},
	});
}

/*
 * Changes the calendar view
 *
 * @param viewName - must be element of global array viewNames
 */
function changeCalendarView(viewName)
{
	currentView = viewName;

	for(var i = 0; i < viewNames.length; i++)
	{
		$("#" + viewNames[i] + "Img").attr("src", "images/" + viewNames[i] + ".png");
	}
	$("#" + currentView + "Img").attr("src", "images/" + currentView + "_highlight.png");

	$("#tripCalendar").fullCalendar('changeView', viewName);
	$("#tripCalendar").fullCalendar('render');
	$("#tripCalendar").fullCalendar('refetchEvents');
}

/*
 * Bring calendar back to today
 */
function gotoToday()
{
	$("#tripCalendar").fullCalendar("gotoDate", new Date());
}

/*
 * Break down a date object into hour(01-12), minute(00-59), period(AM | PM)
 * Return array
 */
function getTime(dateObj)
{
	var hour = dateObj.getHours();
	var minute = dateObj.getMinutes();
	var per = "AM";

	if(hour == 0)
	{
		hour = 12;
		per = "AM";
	}
	else if(hour < 12)
	{
		per = "AM";
	}
	else
	{
		hour -= 12;
		per = "PM";
	}

	hour = hour + "";
	minute = minute + "";
	if(hour.length == 1)
		hour = "0" + hour;
	if(minute.length == 1)
		minute = "0" + minute;
	
	return [hour, minute, per];
}

/*
 * Ask google maps for directions
 */
function mapLookup(originId, destinationId)
{
	var origin = $("#" + originId).val();
	var destination = $("#" + destinationId).val();

	if(origin == "" || destination == "")
		return;

	var request = {
		origin: origin,
		destination: destination,
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.IMPERIAL,
		durationInTraffic: false,
		provideRouteAlternatives: false
	};

	directionsService.route(request, function(response, statusCode)
	{
		if(statusCode == google.maps.DirectionsStatus.OK)
		{
			directionsDisplay.setDirections(response);
			currentRoute = response;
		}
	});
}

/*
 * Opens New Trip dialog to allow user to create a new trip
 */
function openNewTripEditor(startDate, endDate)
{
	$("#newTripEditor").dialog({
		draggable:true,
		title: "Create New Trip",
		height: 600,
		width: 900,
		modal: true,
		resizable: false,
		open: function()
		{
			// Initialize Google Maps
			currentRoute = null;
			$("#createTripMap").html("");
			$("#createTripDirectionsPanel").html("");
			directionsDisplay = new google.maps.DirectionsRenderer();
			var mapOptions = {
				center: {lat: 40.24, lng: -111.67},
				zoom: 6
			}
			googleMap = new google.maps.Map(document.getElementById("createTripMap"), mapOptions);
			directionsDisplay.setMap(googleMap);
			directionsDisplay.setPanel(document.getElementById("createTripDirectionsPanel"));

			// Initialize datepickers
			$("#startDate").datepicker();
			$("#endDate").datepicker();

			// Break apart start and end date
			var startTime = getTime(startDate);
			var endTime = getTime(endDate);

			// Load vehicle list
			$("#vehicleSelect").html("");
			$.ajax({
				dataType: "json",
				type: 'POST',
				url: 'controller/getMyVehicles.php',
				data: {},
				success: function(response)
				{
					var vehicleData = response.aaData;
					$("#vehicleSelect").html("<option value='-1'>Choose Vehicle...</option>");
					for(var i = 0; i < vehicleData.length; i++)
						$("#vehicleSelect").append("<option value='" + vehicleData[i][0] + "'>" + vehicleData[i][3] + " " + vehicleData[i][1] + " " + vehicleData[i][2] + "</option>");
				},
				error: function(response)
				{
					$("#vehicleSelect").html("<option value='-1'>Choose Vehicle...</option>");
					myAlert("Error getting vehicle list: " + response.responseText);
				},
			});

			// Prepopulate data
			$("#startLoc").val('');
			$("#endLoc").val('');
			$("#startDate").datepicker('setDate', startDate);
			$("#startTimeHour").val(startTime[0]);
			$("#startTimeMinute").val(startTime[1]);
			$("#startTimePeriod").val(startTime[2]);
			$("#endDate").datepicker('setDate', endDate);
			$("#endTimeHour").val(endTime[0]);
			$("#endTimeMinute").val(endTime[1]);
			$("#endTimePeriod").val(endTime[2]);
			$("#totalPrice").val('');
		},
		close: function() {
			$(this).dialog('destroy');
		},
		buttons:
		[
			{
				text: "Create Trip",
				id: "createTripBtn",
				click: function()
				{
					if(isValidTrip())
					{
						createTrip();
					}
				},
			},
		],
	});
}

/*
 * Open editor to create vehicle
 */ 
function openVehicleEditor()
{
	$("#vehicleEditor").dialog({
		draggable: true,
		title: "Vehicle Editor",
		height: 330,
		width: 600,
		modal: true,
		resizable: true,
		open: function() {
			$("#vehicleMake").val("");
			$("#vehicleModel").val("");
			$("#vehicleYear").val("");
			$("#vehicleSeatCount").val("");
			$("#vehicleDescription").val("");
		},
		close: function() {
		},
		buttons:
		[
			{
				text: "Save Vehicle",
				id: "saveVehicleBtn",
				click: function() {
					if(isValidVehicle())
					{
						createVehicle();
					}
				},
			},
		],
	});
}

/*
 * Use dialog box for alert system
 */
function myAlert(str)
{
	$("#alertBox").html(str);
	$("#alertBox").dialog({
		width: 450,
		height: 300,
		title: "Alert",
		modal: true,
		buttons:
		[
			{
				text: "OK",
				id: "okBtn",
				click: function() {
					$(this).dialog('close');
				},
			},
		],
	});
}

/*
 * Performs initial setup
 */
$(document).ready(function()
{
	showTrips();
});
