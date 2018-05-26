DROP TABLE IF EXISTS web_user_permission;
DROP TABLE IF EXISTS web_team_member;
DROP TABLE IF EXISTS web_team_subleader;
DROP TABLE IF EXISTS web_team_leader;
DROP TABLE IF EXISTS web_clubroom_picture;
DROP TABLE IF EXISTS web_news_picture;
DROP TABLE IF EXISTS web_team_picture;
DROP TABLE IF EXISTS web_event_picture;
DROP TABLE IF EXISTS web_event_responsible;
DROP TABLE IF EXISTS web_permission;
DROP TABLE IF EXISTS web_document;
DROP TABLE IF EXISTS web_picture;
DROP TABLE IF EXISTS web_document_hierarchy;
DROP TABLE IF EXISTS web_picture_hierarchy;
DROP TABLE IF EXISTS web_event;
DROP TABLE IF EXISTS web_news;
DROP TABLE IF EXISTS web_team;
DROP TABLE IF EXISTS web_clubroom;
DROP TABLE IF EXISTS web_group;
DROP TABLE IF EXISTS web_user_parent;
DROP TABLE IF EXISTS web_parent;
DROP TABLE IF EXISTS web_user;

CREATE TABLE web_user (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NULL,
    nick_name VARCHAR(50) UNIQUE NOT NULL,
    passphrase VARCHAR(255) NULL,
    email VARCHAR(50) NULL,
    email_notifications_enabled BIT NOT NULL DEFAULT 0,
    last_login DATETIME NULL,
    notes VARCHAR(4096) NULL,
    is_child BIT NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_parent (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(50) NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_user_parent (
    user_id INT NOT NULL,
    parent_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES web_user (id),
    CONSTRAINT FOREIGN KEY (parent_id) REFERENCES web_parent (id),
    PRIMARY KEY (user_id, parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_group (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    internal BIT NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_clubroom (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NULL,
    gps_latitude DOUBLE NOT NULL,
    gps_longitude DOUBLE NOT NULL,
    address VARCHAR(1024) NOT NULL,
    directions VARCHAR(1024) NOT NULL,
    description VARCHAR(8192) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_team (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NULL,
    clubroom_id INT NULL,
    boys BIT NOT NULL DEFAULT 0,
    girls BIT NOT NULL DEFAULT 0,
    age_description VARCHAR(100) NOT NULL,
    CONSTRAINT FOREIGN KEY (clubroom_id) REFERENCES web_clubroom (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_news (
    id INT NOT NULL AUTO_INCREMENT,
    text VARCHAR(4096) NULL,
    created DATETIME NOT NULL,
    visible_until DATETIME NULL,
    author_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (author_id) REFERENCES web_user (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_event (
    id INT NOT NULL AUTO_INCREMENT,
    subject VARCHAR(1024) NOT NULL,
    text VARCHAR(4096) NOT NULL,
    created DATETIME NOT NULL,
    date_from DATETIME NOT NULL,
    date_to DATETIME NOT NULL,
    author_id INT NOT NULL,
    responsible_other VARCHAR(100) NULL,
    CONSTRAINT FOREIGN KEY (author_id) REFERENCES web_user (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_picture_hierarchy (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    parent_id INT NULL,
    CONSTRAINT FOREIGN KEY (parent_id) REFERENCES web_parent (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_document_hierarchy (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    parent_id INT NULL,
    CONSTRAINT FOREIGN KEY (parent_id) REFERENCES web_parent (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_picture (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    path VARCHAR(1024) NOT NULL,
    picture_hierarchy_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (picture_hierarchy_id) REFERENCES web_picture_hierarchy (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_document (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    path VARCHAR(1024) NOT NULL,
    document_hierarchy_id INT NOT NULL,
    team_id INT NULL,
    event_id INT NULL,
    CONSTRAINT FOREIGN KEY (document_hierarchy_id) REFERENCES web_document_hierarchy (id),
    CONSTRAINT FOREIGN KEY (team_id) REFERENCES web_team (id),
    CONSTRAINT FOREIGN KEY (event_id) REFERENCES web_event (id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_permission (
    id INT NOT NULL,
    name VARCHAR(250) NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_event_responsible (
    event_id INT NOT NULL,
    user_id INT NULL,
    group_id INT NULL,
    CONSTRAINT FOREIGN KEY (event_id) REFERENCES web_event (id),
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES web_user (id),
    CONSTRAINT FOREIGN KEY (group_id) REFERENCES web_group (id),
    PRIMARY KEY (event_id, user_id, group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_event_picture (
    event_id INT NULL,
    picture_id INT NULL,
    position INT NOT NULL,
    CONSTRAINT FOREIGN KEY (event_id) REFERENCES web_event (id),
    CONSTRAINT FOREIGN KEY (picture_id) REFERENCES web_picture (id),
    PRIMARY KEY (event_id, picture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_team_picture (
    team_id INT NULL,
    picture_id INT NULL,
    position INT NOT NULL,
    CONSTRAINT FOREIGN KEY (team_id) REFERENCES web_team (id),
    CONSTRAINT FOREIGN KEY (picture_id) REFERENCES web_picture (id),
    PRIMARY KEY (team_id, picture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_news_picture (
    news_id INT NULL,
    picture_id INT NULL,
    CONSTRAINT FOREIGN KEY (news_id) REFERENCES web_news (id),
    CONSTRAINT FOREIGN KEY (picture_id) REFERENCES web_picture (id),
    PRIMARY KEY (news_id, picture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_clubroom_picture (
    clubroom_id INT NULL,
    picture_id INT NULL,
    CONSTRAINT FOREIGN KEY (clubroom_id) REFERENCES web_clubroom (id),
    CONSTRAINT FOREIGN KEY (picture_id) REFERENCES web_picture (id),
    PRIMARY KEY (clubroom_id, picture_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_team_leader (
    team_id INT NULL,
    leader_id INT NULL,
    position INT NOT NULL,
    CONSTRAINT FOREIGN KEY (team_id) REFERENCES web_team (id),
    CONSTRAINT FOREIGN KEY (leader_id) REFERENCES web_user (id),
    PRIMARY KEY (team_id, leader_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_team_subleader (
    team_id INT NULL,
    subleader_id INT NULL,
    position INT NOT NULL,
    CONSTRAINT FOREIGN KEY (team_id) REFERENCES web_team (id),
    CONSTRAINT FOREIGN KEY (subleader_id) REFERENCES web_user (id),
    PRIMARY KEY (team_id, subleader_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_team_member (
    team_id INT NULL,
    member_id INT NULL,
    CONSTRAINT FOREIGN KEY (team_id) REFERENCES web_team (id),
    CONSTRAINT FOREIGN KEY (member_id) REFERENCES web_user (id),
    PRIMARY KEY (team_id, member_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE web_user_permission (
    user_id INT NOT NULL,
    permission_id INT NOT NULL,
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES web_user (id),
    CONSTRAINT FOREIGN KEY (permission_id) REFERENCES web_permission (id),
    PRIMARY KEY (user_id, permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
