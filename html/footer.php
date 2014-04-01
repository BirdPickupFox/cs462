			</div>
		</td>
	</tr>
</table>

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

<div id='vehiclePage' style='display:none'>
	<div>This is the vehicle page</div>
</div>

<div id='notifyPage' style='display:none'>
	<div>This is the notification page</div>
</div>

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

<form id='pageForm' method='POST'>
	<input type=hidden name='formAction' id='formAction' value=''>
</form>

</body>
</html>
