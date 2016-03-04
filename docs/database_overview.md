# Database Overview

An overview of the database schema in human-readable form.

```
CREATE TABLE users (
 first_name VARCHAR(128) NOT NULL,
 last_name VARCHAR(64) NOT NULL,
 gender CHAR(1) CHECK(gender = 'M' OR gender = 'F'),
 description VARCHAR(1024),
 contact_number VARCHAR(32),
 address VARCHAR(512) NOT NULL,
 email VARCHAR(128) PRIMARY KEY,
 username VARCHAR(128) NOT NULL UNIQUE,
 password CHAR(128) NOT NULL
);


CREATE TABLE advertise_item (
 type VARCHAR(9) NOT NULL,
 item_name VARCHAR(128) NOT NULL,
 description VARCHAR(1024),
 starting_bid INT,
 bid_deadline DATE,
 buyout INT,
 available_quantity INT DEFAULT 1,
 pickup_location VARCHAR(512),
 return_location VARCHAR(512),
 return_date DATE NOT NULL,
 owner VARCHAR(128) NOT NULL UNIQUE,
 UNIQUE (owner, item_name),
 PRIMARY KEY(owner, item_name),
 FOREIGN KEY(owner) REFERENCES users(email) on delete cascade on update cascade,
 CHECK (type in ('tool', 'appliance', 'furniture', 'book', 'others'))
);

CREATE TABLE bid (
 owner VARCHAR(128),
 item_name VARCHAR(128),
 bidder VARCHAR(128),
 created DATE NOT NULL,
 bid INT NOT NULL,
 PRIMARY KEY(owner, item_name, bid),
 FOREIGN KEY(owner, item_name) REFERENCES advertise_item(owner, item_name) on update cascade on delete cascade
);
```
