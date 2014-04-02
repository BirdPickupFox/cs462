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
 * Opens New Trip dialog to allow user to create a new trip
 */
function openNewTripEditor(startDate, endDate)
{
	$("#newTripEditor").dialog({
		draggable:true,
		title: "Create New Trip",
		height: 320,
		width: 700,
		modal: true,
		resizable: true,
		open: function()
		{
			// Initialize datepickers
			$("#startDate").datepicker();
			$("#endDate").datepicker();

			// Break apart start and end date
			var startTime = getTime(startDate);
			var endTime = getTime(endDate);

			// Prepopulate data
			$("#vehicleSelect").val('-1');
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
				click: function() {
					// TODO create trip
					alert("TODO");
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
