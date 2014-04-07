// Begin Globals
var viewNames = ["agendaDay", "agendaWeek", "month"]; 	// List of calendar views
var currentView = "agendaWeek";				// Current calendar view

var googleMap = null;		// Google Maps map object
var directionsDisplay = null; 	// Google Maps display object
var directionsService = new google.maps.DirectionsService(); // Google Maps direction service object
var currentRoute = null; 	// Google Maps most recently returned route object

var vehicleTable = null; 	// Datatable table for vehicles
var notificationTable = null;	// Datatable table for notifications
var tripMemberTable = null;	// Datatable table for tirp members
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
			startDateTime: startDate.getTime() / 1000,
			endDateTime: endDate.getTime() / 1000,
			origin: origin,
			destination: destination,
			price: price,
		},
		success: function(response)
		{
			$("#tripCalendar").fullCalendar('refetchEvents');
			$("#newTripEditor").dialog('close');
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
			if(response.statusCode == 500)
			{
				myAlert("Error creating vehicle: " + response.errorMsg);
			}
			else if(response.statusCode == 200)
			{
				$("#vehicleEditor").dialog('close');
				vehicleTable.fnDraw();
			}
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
	initNotificationTable();
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
			loadTrips(start, end, callback);
		},
		eventClick: function(calEvent)
		{
			initTripEditor(calEvent.id);
		},
		select: function(startDate, endDate)
		{
			openNewTripEditor(startDate, endDate);
		},
		eventDrop: function(evt, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view)
		{
			updateTrip(evt.id, evt.start, evt.end);
		},
		eventResize: function(evt, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view)
		{
			updateTrip(evt.id, evt.start, evt.end);
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
 * Loads trip members into trip member table
 *
 * @param tripId
 * @param canEdit - boolean, true iff user can edit trip member table accepted boxes
 */
function initTripMemberTable(tripId, canEdit)
{
	tripMemberTable = $("#tripMemberTable").dataTable({
		"aoColumnDefs": [
			{"bSortable": false, "sWidth": "70%", "aTargets":[0]}, // User email
			{"bSortable": false, "sWidth": "30%", "aTargets":[1]}, // Accepted
		],
		"bAutoWidth": false,
		"bLengthChange": false,
		"bPaginate": false,
		"bProcessing": false,
		"bServerSide": true,
		"oLanguage": {
			"sEmptyTable": "Error retrieving members",
		},
		"sAjaxSource": "controller/getTripUsers.php",
		"sDom": "<t>",
		"fnServerData": function(sSource, aoData, fnCallback, oSettings)
		{
			var tripData = new Object();
			tripData.name = 'tripId';
			tripData.value = tripId;

			var editData = new Object();
			editData.name = 'canEdit';
			editData.value = canEdit ? 'true' : 'false';

			var extraDataArray = [tripData, editData];
			var dataArray = aoData.concat(extraDataArray);

			oSettings.jqXHR = $.ajax({
				dataType: "json",
				type: "POST",
				url: sSource,
				data: dataArray,
				success: function(response)
				{
					fnCallback(response);
				},
				error: function(response)
				{
					myAlert("Error loading trip member table: " + response.responseText);
				},
			});
		},
	});
}

/*
 * Initializes notification table
 */
function initNotificationTable()
{
	notificationTable = $("#notificationTable").dataTable({
		"aoColumnDefs": [
			{"bSortable": false, "sWidth": "20%", "aTargets":[0]}, // Date
			{"bSortable": false, "sWidth": "80%", "aTargets":[1]}, // Notification
		],
		"bAutoWidth": false,
		"bLengthChange": false,
		"bPaginate": false,
		"bProcessing": false,
		"bServerSide": true,
		"oLanguage": {
			"sEmptyTable": "You have no notifications",
		},
		"sAjaxSource": "controller/getMyNotifications.php",
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
					myAlert("Error loading notification table: " + response.responseText);
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
function splitTime(dateObj)
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
 * Loads trips onto full calendar
 */
function loadTrips(startDate, endDate, callback)
{
	$.ajax({
		dataType: "json",
		type: "POST",
		url: "controller/getTrips.php",
		data: {
			startDate: startDate.getTime() / 1000,
			endDate: endDate.getTime() / 1000,
			onlyMine: "false", // TODO
			origin: "", // TODO
			destination: "", // TODO
		},
		success: function(response)
		{
			callback(response);
		},
		error: function(response)
		{
			myAlert("Failed to get trips: " + response.responseText);
		},
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
			var startTime = splitTime(startDate);
			var endTime = splitTime(endDate);

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
			$("#startDate").datepicker('destroy');
			$("#endDate").datepicker('destroy');
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
 * Gets trip data to open in editor
 */
function initTripEditor(tripId)
{
	$.ajax({
		dataType: 'json',
		type: 'POST',
		url: 'controller/getTripById.php',
		data: {tripId: tripId},
		success: function(response)
		{
			openTripEditor(response);
		},
		error: function(response)
		{
			myAlert("Error retrieving trip: " + response.responseText);
		},
	});
}

/*
 * Opens up existing trip in editor
 */
function openTripEditor(tripObj)
{
	$("#tripEditor").dialog({
		draggable:true,
		title: "View Trip",
		height: 600,
		width: 900,
		modal: true,
		resizable: false,
		open: function()
		{
			// Initialize Google Maps
			currentRoute = null;
			$("#viewTripMap").html("");
			$("#viewTripDirectionsPanel").html("");
			directionsDisplay = new google.maps.DirectionsRenderer();
			var mapOptions = {
				center: {lat: 40.24, lng: -111.67},
				zoom: 6
			}
			googleMap = new google.maps.Map(document.getElementById("viewTripMap"), mapOptions);
			directionsDisplay.setMap(googleMap);
			directionsDisplay.setPanel(document.getElementById("viewTripDirectionsPanel"));

			// Initialize datepickers
			$("#tripStartDate").datepicker();
			$("#tripEndDate").datepicker();

			// Break apart start and end date
			var startDate = new Date(tripObj.start * 1000);
			var endDate = new Date(tripObj.end * 1000);
			var startTime = splitTime(startDate);
			var endTime = splitTime(endDate);

			// Load vehicle
			$.ajax({
				dataType: "json",
				type: 'POST',
				url: 'controller/getVehicleById.php',
				data: {vehicleId: tripObj.vehicleId},
				success: function(response)
				{
					var vehicle = response.year + " " +
							response.make + " " +
							response.model + " | " +
							response.seat_count + " seats | " +
							response.description;
					$("#tripVehicle").val(vehicle);
				},
				error: function(response)
				{
					myAlert("Error getting vehicle: " + response.responseText);
				},
			});

			// Prepopulate data
			$("#tripStartLoc").val(tripObj.origin);
			$("#tripEndLoc").val(tripObj.destination);
			$("#tripStartDate").datepicker('setDate', startDate);
			$("#tripStartTimeHour").val(startTime[0]);
			$("#tripStartTimeMinute").val(startTime[1]);
			$("#tripStartTimePeriod").val(startTime[2]);
			$("#tripEndDate").datepicker('setDate', endDate);
			$("#tripEndTimeHour").val(endTime[0]);
			$("#tripEndTimeMinute").val(endTime[1]);
			$("#tripEndTimePeriod").val(endTime[2]);
			$("#tripTotalPrice").val(tripObj.price);
			mapLookup("tripStartLoc", "tripEndLoc");

			// Hide buttons based on if user is the driver or not
			if(!tripObj.isDriver)
			{
				$("#updateTripBtn").hide();
				$("#cancelTripBtn").hide();
			}
		},
		close: function() {
			$("#tripStartDate").datepicker('destroy');
			$("#tripEndDate").datepicker('destroy');
			$(this).dialog('destroy');
		},
		buttons:
		[
			{
				text: "Save Changes",
				id: "updateTripBtn",
				click: function()
				{
					var startDate = new Date($("#tripStartDate").val() + " " + 
							$("#tripStartTimeHour").val() + ":" +
							$("#tripStartTimeMinute").val() + " " +
							$("#tripStartTimePeriod").val());
					var endDate = new Date($("#tripEndDate").val() + " " +
							$("#tripEndTimeHour").val() + ":" +
							$("#tripEndTimeMinute").val() + " " +
							$("#tripEndTimePeriod").val());
					updateTrip(tripObj.tripId, startDate, endDate, function() { $("#tripEditor").dialog('close'); });
				},
			},
			{
				text: "Cancel Trip",
				id: "cancelTripBtn",
				click: function()
				{
					deleteTrip(tripObj.tripId);
				},
			},
			{
				text: "View Riders...",
				id: "viewMembersBtn",
				click: function()
				{
					openTripMemberEditor(tripObj.tripId, tripObj.isDriver);
				},
			},
		],
	});
}

/*
 * Opens join trip editor
 *
 * @param tripId
 * @param canEdit - boolean, true iff user can edit trip member table accepted boxes
 */
function openTripMemberEditor(tripId, canEdit)
{
	$("#tripMemberEditor").dialog({
		draggable:true,
		title: "View Trip Members",
		height: 400,
		width: 700,
		modal: true,
		resizable: false,
		open: function()
		{
			if(canEdit)
			{
				$("#joinTripBtn").hide();
				$("#leaveTripBtn").hide();
			}
			else
			{
				$("#saveTripMembersBtn").hide();
			}

			initTripMemberTable(tripId, canEdit);
		},
		close: function()
		{
			tripMemberTable.fnDestroy();
		},
		buttons: [
			{
				text: "Join Trip",
				id: "joinTripBtn",
				click: function()
				{
					myAlert("TODO join trip");
				},
			},
			{
				text: "Leave Trip",
				id: "leaveTripBtn",
				click: function()
				{
					myAlert("TODO leave trip");
				},
			},
			{
				text: "Save and Send Notifications",
				id: "saveTripMembersBtn",
				click: function()
				{
					myAlert("TODO save trip members");
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
 * Update a trip's start and end time
 */
function updateTrip(tripId, start, end, callback)
{
	if(typeof callback == "undefined")
	{
		callback = function(){};
	}

	$.ajax({
		dataType: 'json',
		type: 'POST',
		url: 'controller/updateTrip.php',
		data: {
			tripId: tripId,
			startTime: start.getTime() / 1000,
			endTime: end.getTime() / 1000,
		},
		success: function(response)
		{
			$("#tripCalendar").fullCalendar('refetchEvents');
			callback();
		},
		error: function(response)
		{
			myAlert("Error updating trip: " + response.responseText);
			$("#tripCalendar").fullCalendar('refetchEvents');
		},
	});
}

/*
 * Cancels a trip
 */
function deleteTrip(tripId)
{
	$.ajax({
		dateType: "json",
		type: 'POST',
		url: 'controller/deleteTrip.php',
		data: {
			tripId: tripId,
		},
		success: function(response)
		{
			$("#tripEditor").dialog('close');
			$("#tripCalendar").fullCalendar('refetchEvents');
		},
		error: function(response)
		{
			myAlert("Error deleting trip: " + response.responseText);
		},
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
