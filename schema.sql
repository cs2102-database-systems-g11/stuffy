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
-- Name: postgres; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON DATABASE postgres IS 'default administrative connection database';


--
-- Name: StuffSharing; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA "StuffSharing";


ALTER SCHEMA "StuffSharing" OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = "StuffSharing", pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: Advertise_Item; Type: TABLE; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

CREATE TABLE "Advertise_Item" (
    type character varying(9) NOT NULL,
    item_name character varying(128) NOT NULL,
    description character varying(1024),
    starting_bid integer,
    bid_deadline date,
    buyout integer,
    available_quantity integer DEFAULT 1,
    pickup_location character varying(512),
    return_location character varying(512),
    return_date date NOT NULL,
    owner character varying(128) NOT NULL,
    CONSTRAINT type CHECK (((((((type)::text = 'tool'::text) OR ((type)::text = 'appliance'::text)) OR ((type)::text = 'furniture'::text)) OR ((type)::text = 'book'::text)) OR ((type)::text = 'others'::text)))
);


ALTER TABLE "Advertise_Item" OWNER TO postgres;

--
-- Name: Bid; Type: TABLE; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

CREATE TABLE "Bid" (
    highest_bidder character varying(128) NOT NULL,
    created date NOT NULL,
    num_bidders integer DEFAULT 1 NOT NULL,
    current_bid integer NOT NULL,
    item_name character varying(128) NOT NULL,
    owner character varying(128) NOT NULL
);


ALTER TABLE "Bid" OWNER TO postgres;

--
-- Name: Users; Type: TABLE; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

CREATE TABLE "Users" (
    first_name character varying(128) NOT NULL,
    last_name character varying(64) NOT NULL,
    gender character(1) NOT NULL,
    description character varying(1024),
    contact_number character varying(32),
    address character varying(512) NOT NULL,
    email character varying(128) NOT NULL,
    username character varying(128) NOT NULL,
    password character(128) NOT NULL,
    CONSTRAINT gender CHECK (((gender = 'M'::bpchar) OR (gender = 'F'::bpchar)))
);


ALTER TABLE "Users" OWNER TO postgres;

--
-- Name: Advertise_Item_pkey; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Advertise_Item"
    ADD CONSTRAINT "Advertise_Item_pkey" PRIMARY KEY (item_name, owner);


--
-- Name: Bid_pkey; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Bid"
    ADD CONSTRAINT "Bid_pkey" PRIMARY KEY (highest_bidder, item_name, owner);


--
-- Name: Users_pkey; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Users"
    ADD CONSTRAINT "Users_pkey" PRIMARY KEY (email);


--
-- Name: Users_username_key; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Users"
    ADD CONSTRAINT "Users_username_key" UNIQUE (username);


--
-- Name: email; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Users"
    ADD CONSTRAINT email UNIQUE (email);


--
-- Name: item_name; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Advertise_Item"
    ADD CONSTRAINT item_name UNIQUE (item_name);


--
-- Name: owner; Type: CONSTRAINT; Schema: StuffSharing; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY "Advertise_Item"
    ADD CONSTRAINT owner UNIQUE (owner);


--
-- Name: Advertise_Item_owner_fkey; Type: FK CONSTRAINT; Schema: StuffSharing; Owner: postgres
--

ALTER TABLE ONLY "Advertise_Item"
    ADD CONSTRAINT "Advertise_Item_owner_fkey" FOREIGN KEY (owner) REFERENCES "Users"(email) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: item_name; Type: FK CONSTRAINT; Schema: StuffSharing; Owner: postgres
--

ALTER TABLE ONLY "Bid"
    ADD CONSTRAINT item_name FOREIGN KEY (item_name) REFERENCES "Advertise_Item"(item_name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: owner; Type: FK CONSTRAINT; Schema: StuffSharing; Owner: postgres
--

ALTER TABLE ONLY "Bid"
    ADD CONSTRAINT owner FOREIGN KEY (owner) REFERENCES "Advertise_Item"(owner) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

