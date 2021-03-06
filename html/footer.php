			</div>
		</td>
	</tr>
</table>

<!-- Trip Page -->
<div id='tripPage' style='display:none'>
	<table id="calendarViewSelect">
		<tr>
			<td>
				<table>
					<tr><td onclick="gotoToday()" onmouseover="$('#todayImg').attr('src', 'images/today view highlight.png');" onmouseout="$('#todayImg').attr('src', 'images/today view.png')">
						<img id="todayImg" src="images/today view.png">
					</td></tr>
					<tr><td>Today</td></tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td onclick="changeCalendarView('agendaDay')"><img id="agendaDayImg" src="images/agendaDay.png"></td></tr>
					<tr><td>Day</td></tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td onclick="changeCalendarView('agendaWeek')"><img id="agendaWeekImg" src="images/agendaWeek_highlight.png"></td></tr>
					<tr><td>Week</td></tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td onclick="changeCalendarView('month')"><img id="monthImg" src="images/month.png"></td></tr>
					<tr><td>Month</td></tr>
				</table>
			</td>
		</tr>
	</table>
	<div id='tripCalendar'></div>
</div>

<!-- My Vehicles Page -->
<div id='vehiclePage' style='display:none'>
	<button id='registerVehicleBtn' onclick='openVehicleEditor()'>Register Vehicle</button>
	<table id='vehicleTable'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Make</th>
				<th>Model</th>
				<th>Year</th>
				<th>Seat Count</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<!-- My Notifications Page -->
<div id='notifyPage' style='display:none'>
	<table id='notificationTable'>
		<thead>
			<tr>
				<th>Date/Time</th>
				<th>Notification</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<!-- Search Page -->
<div id='searchPage' style='display:none'>
	<div id='searchQuery'>
		<input name='searchBox' id='searchBox'>
		<button id='searchTripBtn' onclick='searchTable.fnDraw()'>Search</button>
	</div>
	<table id='searchResultsTable'>
		<thead>
			<tr>
				<th>Trip ID</th>
				<th>Origin</th>
				<th>Destination</th>
				<th>Departure</th>
				<th>Arrival</th>
				<th>Seats Filled</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<!-- Registration Editor -->
<div id='registrationEditor' style='display:none'>
	<table>
		<tr>
			<td>Email</td>
			<td><input type=text id='regEmail'></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type=password id='regPwd'></td>
		</tr>
	</table>
</div>

<!-- New Trip Editor -->
<div id='newTripEditor' style='display:none'>
	<table id='newTripTable'>
		<tr>
			<th>Vehicle</th>
			<td colspan=3>
				<select id='vehicleSelect' class='wide'>
					<option value='-1'>Choose Vehicle...</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Start Location</th>
			<td><input type=text id='startLoc' onkeypress='mapLookup("startLoc", "endLoc")'></td>
			<th>End Location</th>
			<td><input type=text id='endLoc' onkeypress='mapLookup("startLoc", "endLoc")'></td>
		</tr>
		<tr>
			<th>Departure Date</th>
			<td><input type=text id='startDate'></td>
			<th>Departure Time</th>
			<td>
				<table>
					<tr>
						<td>
							<select id='startTimeHour'>
								<?php
								for($i=1; $i <= 12; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>:</td>
						<td>
							<select id='startTimeMinute'>
								<?php
								for($i=0; $i <= 60; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>
							<select id='startTimePeriod'>
								<option>AM</option>
								<option>PM</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Arrival Date</th>
			<td><input type=text id='endDate'></td>
			<th>Arrival Time</th>
			<td>
				<table>
					<tr>
						<td>
							<select id='endTimeHour'>
								<?php
								for($i=1; $i <= 12; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>:</td>
						<td>
							<select id='endTimeMinute'>
								<?php
								for($i=0; $i < 60; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>
							<select id='endTimePeriod'>
								<option>AM</option>
								<option>PM</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Total Price</th>
			<td colspan=3><input type=text id='totalPrice' class='wide'></td>
		</tr>
	</table>
	<table style='width:100%;height:100%'>
		<tr>
			<td style='width:50%'><div id="createTripMap" style="width:100%;height:100%"></div></td>
			<td style='width:50%'><div id="createTripDirectionsPanel" style="width:100%;height:100%"></div></td>
		</tr>
	</table>
</div>

<!-- Trip Editor -->
<div id='tripEditor' style='display:none'>
	<table id='tripTable'>
		<tr>
			<th>Vehicle</th>
			<td colspan=3><input type=text id='tripVehicle' class='wide' DISABLED></td>
		</tr>
		<tr>
			<th>Start Location</th>
			<td><input type=text id='tripStartLoc' DISABLED></td>
			<th>End Location</th>
			<td><input type=text id='tripEndLoc' DISABLED></td>
		</tr>
		<tr>
			<th>Departure Date</th>
			<td><input type=text id='tripStartDate'></td>
			<th>Departure Time</th>
			<td>
				<table>
					<tr>
						<td>
							<select id='tripStartTimeHour'>
								<?php
								for($i=1; $i <= 12; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>:</td>
						<td>
							<select id='tripStartTimeMinute'>
								<?php
								for($i=0; $i <= 60; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>
							<select id='tripStartTimePeriod'>
								<option>AM</option>
								<option>PM</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Arrival Date</th>
			<td><input type=text id='tripEndDate'></td>
			<th>Arrival Time</th>
			<td>
				<table>
					<tr>
						<td>
							<select id='tripEndTimeHour'>
								<?php
								for($i=1; $i <= 12; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>:</td>
						<td>
							<select id='tripEndTimeMinute'>
								<?php
								for($i=0; $i < 60; $i++)
								{
									if($i < 10)
										echo "<option>0$i</option>";
									else
										echo "<option>$i</option>";
								}
								?>
							</select>
						</td>
						<td>
							<select id='tripEndTimePeriod'>
								<option>AM</option>
								<option>PM</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>Total Price</th>
			<td colspan=3><input type=text id='tripTotalPrice' class='wide' DISABLED></td>
		</tr>
	</table>
	<table style='width:100%;height:100%'>
		<tr>
			<td style='width:50%'><div id="viewTripMap" style="width:100%;height:100%"></div></td>
			<td style='width:50%'><div id="viewTripDirectionsPanel" style="width:100%;height:100%"></div></td>
		</tr>
	</table>
</div>

<!-- Vehicle Editor -->
<div id='vehicleEditor' style='display:none'>
	<table id='vehicleEditorTable'>
		<tr>
			<th>Make</th>
			<td><input class='wide' type=text id='vehicleMake'></td>
		</tr>
		<tr>
			<th>Model</th>
			<td><input class='wide' type=text id='vehicleModel'></td>
		</tr>
		<tr>
			<th>Year</th>
			<td><input class='wide' type=text id='vehicleYear'></td>
		</tr>
		<tr>
			<th>Seat Count</th>
			<td><input class='wide' type=text id='vehicleSeatCount'></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><input class='wide' type=text id='vehicleDescription'></td>
		</tr>
	</table>
</div>

<!-- Trip Member Editor -->
<div id='tripMemberEditor' style='display:none'>
	<table id='tripMemberTable'>
		<thead>
			<th>Email</th>
			<th>Accepted</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<!-- Universal Alert Box -->
<div id='alertBox' style='display:none'>
</div>

<!-- Universal Page Form -->
<form id='pageForm' method='POST'>
	<input type=hidden name='formAction' id='formAction' value=''>
</form>

<!-- Footer -->
<footer>
	<?php echo $inject;?>
</footer>

</body>
</html>
