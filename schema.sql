--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: advertise_item; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE advertise_item (
    owner character varying(128) NOT NULL,
    item_name character varying(128) NOT NULL,
    type character varying(9) NOT NULL,
    description character varying(1024),
    starting_bid integer,
    bid_deadline date NOT NULL,
    buyout integer,
    available_quantity integer DEFAULT 1,
    pickup_location character varying(512),
    return_location character varying(512),
    return_date date NOT NULL,
    CONSTRAINT advertise_item_type_check CHECK (((type)::text = ANY ((ARRAY['Tool'::character varying, 'Appliance'::character varying, 'Furniture'::character varying, 'Book'::character varying, 'Others'::character varying])::text[])))
);


ALTER TABLE advertise_item OWNER TO postgres;

--
-- Name: bid; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE bid (
    owner character varying(128) NOT NULL,
    item_name character varying(128) NOT NULL,
    bid integer NOT NULL,
    bidder character varying(128),
    created date NOT NULL
);


ALTER TABLE bid OWNER TO postgres;

--
-- Name: borrow; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW borrow AS
 SELECT b.item_name,
    b.owner,
    max(b.bid) AS winning_bid
   FROM advertise_item a,
    bid b
  WHERE (((((a.owner)::text = (b.owner)::text) AND ((a.item_name)::text = (b.item_name)::text)) AND (a.bid_deadline < now())) AND (b.bid >= a.starting_bid))
  GROUP BY b.item_name, b.owner;


ALTER TABLE borrow OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    email character varying(128) NOT NULL,
    username character varying(128) NOT NULL,
    password character(128) NOT NULL,
    first_name character varying(128) NOT NULL,
    last_name character varying(64) NOT NULL,
    gender character(1),
    description character varying(1024),
    contact_number character varying(32),
    address character varying(512) NOT NULL,
    CONSTRAINT users_gender_check CHECK (((gender = 'M'::bpchar) OR (gender = 'F'::bpchar)))
);


ALTER TABLE users OWNER TO postgres;

--
-- Name: advertise_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY advertise_item
    ADD CONSTRAINT advertise_item_pkey PRIMARY KEY (owner, item_name);


--
-- Name: bid_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY bid
    ADD CONSTRAINT bid_pkey PRIMARY KEY (owner, item_name, bid);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (email);


--
-- Name: users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: advertise_item_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY advertise_item
    ADD CONSTRAINT advertise_item_owner_fkey FOREIGN KEY (owner) REFERENCES users(email) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: bid_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY bid
    ADD CONSTRAINT bid_owner_fkey FOREIGN KEY (owner, item_name) REFERENCES advertise_item(owner, item_name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

