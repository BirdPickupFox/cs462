DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS trips;
DROP TABLE IF EXISTS trip_users;
DROP TABLE IF EXISTS requests;

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
	start_loc varchar(255) not null,
	end_loc varchar(255) not null,
	departure_date varchar(255) not null,
	departure_time varchar(255) not null,
	arrival_date varchar(255) not null,
	arrival_time varchar(255) not null,
	google_calendar_id varchar(255) not null,
	vehicle varchar(20),
	total_cost integer,
	foreign key (vehicle) references vehicles(vehicle_id)
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
	departure_date_range_start varchar(255),
	departure_date_range_end varchar(255),
	arrival_date_range_start varchar(255),
	arrival_date_range_end varchar(255),
	foreign key (user_email) references users(email)
);
