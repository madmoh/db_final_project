create table driver (
	license_no char(7),
	fname varchar(32),
	lname varchar(32),
	age integer,
	bdate date,
	city varchar(32),
	password char(100),
	primary key (license_no)
);

create table car (
	plate_no char(5),
	license_no char(7),
	car_type varchar(32),
	fines double,
	city varchar(32),
	primary key (plate_no),
	constraint carsfk 
		foreign key (license_no) references driver(license_no) 
			on delete cascade on update cascade
);