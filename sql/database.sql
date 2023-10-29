DROP TABLE IF EXISTS state;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS season;

CREATE TABLE season (
  id int AUTO_INCREMENT PRIMARY KEY,
  name varchar(128),
  created datetime default current_timestamp(),
  guid varchar(40) default uuid()
);

CREATE TABLE event (
  id int AUTO_INCREMENT PRIMARY KEY,
  season_id int references season(id),
  name varchar(128),
  rules text,
  created datetime default current_timestamp(),
  guid varchar(40) default uuid()
);

ALTER TABLE event ADD CONSTRAINT fk_event_season_id FOREIGN KEY (season_id) REFERENCES season (id);

CREATE TABLE post (
  id int AUTO_INCREMENT PRIMARY KEY,
  event_id int references event(id),
  season_id int references season(id),
  author varchar(128),
  group_name varchar(128),
  post_url varchar(256),
  page_url varchar(256),
  post text,
  valid int default 0,
  invalid_reason varchar(256),
  extra_data text,
  created datetime default current_timestamp(),
  guid varchar(40) default uuid()
);

ALTER TABLE post ADD CONSTRAINT fk_post_event_id FOREIGN KEY (event_id) REFERENCES event (id);
ALTER TABLE post ADD CONSTRAINT fk_post_season_id FOREIGN KEY (season_id) REFERENCES season (id);

CREATE TABLE state (
  event_id int references event(id),
  season_id int references season(id),
  last_page_url varchar(256),
  last_post_url varchar(256)
);
