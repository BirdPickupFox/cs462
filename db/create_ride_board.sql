DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS trips;
DROP TABLE IF EXISTS trip_users;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS auth;

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
	created_time integer not null,
	foreign key (user_email) references users(email)
);

CREATE TABLE auth
(
	auth_id integer primary key,
	token varchar(255)
);

-- Insert test data
INSERT INTO users VALUES ('a@a.com', 'a');
INSERT INTO users VALUES ('b@b.com', 'b');
INSERT INTO users VALUES ('fox016@gmail.com', 'fox');
INSERT INTO users VALUES ('rlbird22@gmail.com', 'bird');
INSERT INTO users VALUES ('pickup21@gmail.com', 'pickup');

INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2000, 'Toyota', 'Camry', 5, 'Large trunk', 'a@a.com');
INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2005, 'Honda', 'Civic', 5, 'Hybrid, 50 mpg', 'b@b.com');
INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2005, 'Honda', 'Civic', 5, 'Hybrid, 50 mpg', 'fox016@gmail.com');
INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2005, 'Honda', 'Civic', 5, 'Hybrid, 50 mpg', 'rlbird22@gmail.com');
INSERT INTO vehicles (year, make, model, seat_count, description, owner)
	VALUES (2005, 'Honda', 'Civic', 5, 'Hybrid, 50 mpg', 'pickup21@gmail.com');

INSERT INTO trips (origin_loc, destination_loc, departure_date_time, arrival_date_time, vehicle_id, total_cost)
	VALUES ('Provo, UT, USA', 'San Diego, CA, USA', 1396869000, 1396915800, 1, 200);
INSERT INTO trips (origin_loc, destination_loc, departure_date_time, arrival_date_time, vehicle_id, total_cost)
	VALUES ('San Deigo, CA, USA', 'Provo, UT, USA', 1397133600, 1397180400, 1, 200);

INSERT INTO trip_users VALUES ('a@a.com', 1, 1);
INSERT INTO trip_users VALUES ('b@b.com', 1, 0);
INSERT INTO trip_users VALUES ('a@a.com', 2, 1);

INSERT INTO notifications VALUES ('a@a.com', 'Test Notification', 1396645000);
INSERT INTO notifications VALUES ('b@b.com', 'Test Notification', 1396645000);
INSERT INTO notifications VALUES ('fox016@gmail.com', 'Test Notification', 1396645000);
INSERT INTO notifications VALUES ('rlbird22@gmail.com', 'Test Notification', 1396645000);
INSERT INTO notifications VALUES ('pickup21@gmail.com', 'Test Notification', 1396645000);

INSERT INTO auth VALUES (1, 'bad_token');
