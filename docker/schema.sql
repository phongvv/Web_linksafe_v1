CREATE TABLE IF NOT EXISTS app_category (
	id serial4 NOT NULL,
	"name" varchar(100) NULL,
	description varchar(100) NULL,
	CONSTRAINT app_category_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS app (
	id serial4 NOT NULL,
	"name" varchar(100) NULL,
	category_id int4 NULL,
	index_name varchar(100) NULL,
	CONSTRAINT app_pkey PRIMARY KEY (id)
);


CREATE TABLE IF NOT EXISTS protocol_category (
	id serial4 NOT NULL,
	"name" varchar(100) NULL,
	description varchar(100) NULL,
	CONSTRAINT protocol_category_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS protocol (
	id serial4 NOT NULL,
	"name" varchar(100) NULL,
	category_id int4 NULL,
	index_name varchar(100) NULL,
	CONSTRAINT protocol_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS web (
	id serial4 NOT NULL,
	"domain" varchar(100) NULL,
	url varchar(100) NULL,
	category_id int4 NULL,
	CONSTRAINT web_pkey PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS web_category (
	id serial4 NOT NULL,
	"name" varchar(100) NULL,
	description varchar(100) NULL,
	CONSTRAINT web_category_pkey PRIMARY KEY (id)
);
