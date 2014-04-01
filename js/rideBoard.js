/*
 * Opens dialog box for user to sign in
 */
function signIn()
{
	alert("TODO");
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
		},
		close: function() {
		},
		buttons:
		[
			{
				text: "Create Account",
				id: "createAccountBtn",
				click: function(){
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
 * Navigates to the Trips page
 */
function showTrips()
{
	selectNavigation("tripNav");
	$("#dynamicContent").html($("#tripPage").html());
	initTripCalendar();
}

/*
 * Navigates to the My Vehicles page
 */
function showMyVehicles()
{
	selectNavigation("vehicleNav");
	$("#dynamicContent").html($("#vehiclePage").html());
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

// Globals for calendar
var viewNames = ["agendaDay", "agendaWeek", "month"];
var currentView = "agendaWeek";

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
		eventClick: function()
		{
			// TODO open trip editor
		},
		select: function(startDate, endDate)
		{
			// TODO create trip
		},
		viewDisplay: function(view)
		{
			// TODO react to change in view (probably not needed)
		},
		eventDrop: function(evt, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view)
		{
			// TODO update trip
		},
		eventResize: function(evt, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view)
		{
			// TODO update trip
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
 * Changes the calendar view
 *
 * @param viewName - must be element of global viewNames
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
 * Performs initial setup
 */
$(document).ready(function()
{
	showTrips();
});
