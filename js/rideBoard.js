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
	alert("TODO");
}

/*
 * Opens dialog box for user to register
 */
function register()
{
	alert("TODO");
}

/*
 * Navigates to the Trips page
 */
function showTrips()
{
	selectNavigation("tripNav");
	$("#dynamicContent").html($("#tripPage").html());
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
 * Performs initial setup
 */
$(document).ready(function()
{
	showTrips();
});
