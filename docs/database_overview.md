# Database Overview

An overview of the database schema in human-readable form.

```sql
CREATE TABLE users (
 email VARCHAR(128) PRIMARY KEY,
 username VARCHAR(128) NOT NULL UNIQUE,
 password CHAR(128) NOT NULL,
 first_name VARCHAR(128) NOT NULL,
 last_name VARCHAR(64) NOT NULL,
 gender CHAR(1) CHECK(gender = 'M' OR gender = 'F'),
 description VARCHAR(1024),
 contact_number VARCHAR(32),
 address VARCHAR(512) NOT NULL
);


CREATE TABLE advertise_item (
 owner VARCHAR(128) NOT NULL,
 item_name VARCHAR(128) NOT NULL,
 type VARCHAR(9) NOT NULL,
 description VARCHAR(1024),
 starting_bid INT DEFAULT 0,
 bid_deadline DATE NOT NULL,
 buyout INT,
 available_quantity INT DEFAULT 1,
 pickup_location VARCHAR(512),
 return_location VARCHAR(512),
 return_date DATE NOT NULL,
 PRIMARY KEY(owner, item_name),
 FOREIGN KEY(owner) REFERENCES users(email) on delete cascade on update cascade,
 CHECK (type in ('Tool', 'Appliance', 'Furniture', 'Book', 'Others'))
);

CREATE TABLE bid (
 owner VARCHAR(128),
 item_name VARCHAR(128),
 bid INT NOT NULL,
 bidder VARCHAR(128) NOT NULL,
 created TIMESTAMP NOT NULL,
 PRIMARY KEY(owner, item_name, bid),
 FOREIGN KEY(owner, item_name) REFERENCES advertise_item(owner, item_name) on update cascade on delete cascade,
 FOREIGN KEY(bidder) REFERENCES users(email) on update cascade on delete cascade
);

CREATE VIEW borrow AS
 SELECT b.owner, b.item_name, b.bid, b.bidder
 FROM bid b
 WHERE EXISTS ( SELECT b_1.item_name, b_1.owner
 FROM advertise_item a, bid b_1
 WHERE a.owner = b_1.owner 
 AND a.item_name = b_1.item_name 
 AND a.bid_deadline < now() 
 AND b_1.bid >= a.starting_bid
 GROUP BY b_1.item_name, b_1.owner
 HAVING b.item_name = b_1.item_name 
 AND b.owner = b_1.owner
 AND b.bid = max(b_1.bid) );
```
