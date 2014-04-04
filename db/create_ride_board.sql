DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS trips;
DROP TABLE IF EXISTS trip_users;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS notifications;

CREATE TABLE users
(
	email varchar(255) primary key,
	password varchar(255) not null
);

CREATE TABLE vehicles
(
	vehicle_id integer primary key autoincrement,
	year integer not null,
	make varchar(255) not null,
	model varchar(255) not null,
	seat_count integer not null,
	description varchar(4000),
	owner varchar(255) not null,
	foreign key (owner) references users(email)
);

CREATE TABLE trips
(
	trip_id integer primary key autoincrement,
	origin_loc varchar(255) not null,
	destination_loc varchar(255) not null,
	departure_date_time integer not null,
	arrival_date_time integer not null,
	vehicle_id varchar(20) not null,
	google_calendar_id varchar(255),
	total_cost integer,
	foreign key (vehicle_id) references vehicles(vehicle_id)
);

CREATE TABLE trip_users
(
	user_email varchar(255) not null,
	trip_id integer not null,
	request_accepted integer,
	foreign key (user_email) references users(email),
	foreign key (trip_id) references trips(trip_id)
);

CREATE TABLE requests
(
	user_email varchar(255) not null,
	start_loc varchar(255),
	end_loc varchar(255),
	departure_date_range_start integer,
	departure_date_range_end integer,
	arrival_date_range_start integer,
	arrival_date_range_end integer,
	foreign key (user_email) references users(email)
);

CREATE TABLE notifications
(
	user_email varchar(255) not null,
	text varchar(4000) not null,
	foreign key (user_email) references users(email)
);

-- Insert test data
INSERT INTO users VALUES ('a@a.com', 'a');
INSERT INTO users VALUES ('b@b.com', 'b');

INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2000, 'Toyota', 'Camry', 5, 'Large trunk', 'a@a.com');
INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2005, 'Honda', 'Civic', 5, 'Hybrid, 50 mpg', 'b@b.com');

INSERT INTO trips (origin_loc, destination_loc, departure_date_time, arrival_date_time, vehicle_id, total_cost)
	VALUES ('Provo, UT, USA', 'San Diego, CA, USA', 1396269000, 1396306800, 1, 200);
INSERT INTO trips (origin_loc, destination_loc, departure_date_time, arrival_date_time, vehicle_id, total_cost)
	VALUES ('San Deigo, CA, USA', 'Provo, UT, USA', 1396533600, 1396580400, 1, 200);

INSERT INTO trip_users VALUES ('a@a.com', 1, 1);
INSERT INTO trip_users VALUES ('b@b.com', 1, 0);
INSERT INTO trip_users VALUES ('a@a.com', 2, 1);
INSERT INTO trip_users VALUES ('b@b.com', 2, 0);
