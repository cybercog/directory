
CREATE DATABASE yii_directory
    WITH OWNER = postgres
        ENCODING = 'UTF8'
        TABLESPACE = pg_default
        LC_COLLATE = 'Russian_Russia.1251'
        LC_CTYPE = 'Russian_Russia.1251'
    CONNECTION LIMIT = -1;

-- для консольного клиента
\connect yii_directory

CREATE TYPE types_t_types AS ENUM ('string', 'text', 'image', 'file');


CREATE SEQUENCE types_t_id_counter CYCLE;
CREATE TABLE types_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('types_t_id_counter'),
    name VARCHAR(255) NOT NULL,
    type types_t_types NOT NULL DEFAULT 'string',
    description TEXT DEFAULT NULL,
    validate TEXT DEFAULT NULL,
    UNIQUE (name)
);
CREATE INDEX types_t_id_index ON types_t (id);
CREATE INDEX types_t_name_index ON types_t (name);
CREATE INDEX types_t_type_index ON types_t (type);

CREATE VIEW types_tolower_v (id, name, type, description, validate) AS
SELECT t.id AS id, lower(t.name) AS name, 
        t.type AS type, lower(t.description) AS description, 
        lower(t.validate) AS validate,
        t.name AS original_name,
        t.description AS original_description, 
        t.validate AS original_validate
FROM types_t t;


CREATE TYPE visible_type AS ENUM ('N', 'Y');


CREATE FUNCTION check_data_input (INTEGER, VARCHAR(255), TEXT) RETURNS BOOLEAN AS $$
BEGIN 
    RETURN 
        CASE 
            WHEN (SELECT MAX(type) FROM types_t WHERE id=$1)='string' THEN $2 IS NOT NULL
            ELSE $3 IS NOT NULL
        END;
END;
$$ LANGUAGE plpgsql;


CREATE SEQUENCE data_t_id_counter CYCLE;
CREATE TABLE data_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('data_t_id_counter'),
    type_id INTEGER NOT NULL REFERENCES types_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    value VARCHAR(255) DEFAULT NULL,
    text TEXT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    visible visible_type NOT NULL DEFAULT 'Y',
    CHECK (  check_data_input(type_id, value, text) )
);
CREATE INDEX data_t_id_index ON data_t (id);
CREATE INDEX data_t_type_index ON data_t (type_id);
CREATE INDEX data_t_value_index ON data_t (value);

CREATE VIEW data_tolower_v AS
SELECT d.id AS id, t.id AS type_id, lower(t.name) AS type_name,
        t.type AS type_type, lower(d.value) AS value, lower(d.text) AS text,
        lower(d.description) AS description, d.visible AS visible,
        t.name AS original_type_name, d.value AS original_value,
        d.text AS original_text, d.description AS original_description
FROM data_t d
    INNER JOIN types_t t ON t.id = d.type_id;


CREATE SEQUENCE records_t_id_counter CYCLE;
CREATE TABLE records_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('records_t_id_counter'),
    visible visible_type NOT NULL DEFAULT 'Y'
);
CREATE INDEX records_t_id_index ON records_t (id);
CREATE INDEX records_t_visible_index ON records_t (visible);

CREATE TABLE records_data_t
(
    record_id INTEGER NOT NULL REFERENCES records_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    data_id INTEGER NOT NULL REFERENCES data_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible visible_type NOT NULL DEFAULT 'Y',
    position INTEGER NOT NULL DEFAULT 0,
    sub_position INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY (record_id, data_id)
);
CREATE INDEX records_data_t_record_id_index ON records_data_t (record_id);
CREATE INDEX records_data_t_data_id_index ON records_data_t (data_id);
CREATE INDEX records_data_t_visible_index ON records_data_t (visible);
CREATE INDEX records_data_t_position_index ON records_data_t (position);
CREATE INDEX records_data_t_sub_position_index ON records_data_t (sub_position);

CREATE SEQUENCE directories_t_id_counter CYCLE;
CREATE TABLE directories_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('directories_t_id_counter'),
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    visible visible_type NOT NULL DEFAULT 'Y',
    UNIQUE (name)
);
CREATE INDEX directories_t_id_index ON directories_t (id);
CREATE INDEX directories_t_name_index ON directories_t (name);
CREATE INDEX directories_t_visible_index ON directories_t (visible);

CREATE VIEW directories_tolower_v AS
SELECT d.id AS id,
    lower(d.name) AS name,
    d.name AS original_name,
    lower(d.description) AS description,
    d.description AS original_description,
    d.visible AS visible
FROM directories_t d;

CREATE TABLE records_directory_t
(
    record_id INTEGER NOT NULL REFERENCES records_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    directory_id INTEGER NOT NULL REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (record_id, directory_id)
);
CREATE INDEX records_directory_t_record_id_index ON records_directory_t (record_id);
CREATE INDEX records_directory_t_directory_id_index ON records_directory_t (directory_id);
CREATE INDEX records_directory_t_visible_index ON records_directory_t (visible);

CREATE SEQUENCE branches_t_id_counter CYCLE;
CREATE TABLE branches_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('branches_t_id_counter'),
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    visible visible_type NOT NULL DEFAULT 'Y'
);
CREATE INDEX branches_t_id_index ON branches_t (id);
CREATE INDEX branches_t_name_index ON branches_t (name);
CREATE INDEX branches_t_visible_index ON branches_t (visible);

CREATE SEQUENCE branches_directories_t_id_counter CYCLE;
CREATE TABLE branches_directories_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('branches_directories_t_id_counter'),
    this_branch_id INTEGER NOT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    parent_branch_id INTEGER DEFAULT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    directory_id INTEGER NOT NULL REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible visible_type NOT NULL DEFAULT 'Y',
    UNIQUE (this_branch_id, parent_branch_id, directory_id)
);
CREATE INDEX branches_directories_t_id_index ON branches_directories_t (id);
CREATE INDEX branches_directories_t_this_branch_id_index ON branches_directories_t (this_branch_id);
CREATE INDEX branches_directories_t_parent_branch_id_index ON branches_directories_t (parent_branch_id);
CREATE INDEX branches_directories_t_directory_id_index ON branches_directories_t (directory_id);
CREATE INDEX branches_directories_t_visible_index ON branches_directories_t (visible);

CREATE FUNCTION check_directory_ids (INTEGER, INTEGER) RETURNS BOOLEAN AS $$
BEGIN
    RETURN ((SELECT MAX(bdt.directory_id) 
                FROM branches_directories_t bdt 
                WHERE $1 = bdt.id) =  
            (SELECT MAX(rdt.directory_id) 
                FROM records_directory_t rdt 
                WHERE $2 = rdt.id));
END;
$$ LANGUAGE plpgsql;

CREATE TABLE records_branches_t
(
    branch_connect_id INTEGER NOT NULL REFERENCES branches_directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    record_connect_id INTEGER NOT NULL REFERENCES records_directory_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (branch_connect_id, record_connect_id),
    CHECK (  check_directory_ids(branch_connect_id, record_connect_id) )
);
CREATE INDEX records_branches_t_branch_connect_id_index ON records_branches_t (branch_connect_id);
CREATE INDEX records_branches_t_record_connect_id_index ON records_branches_t (record_connect_id);
CREATE INDEX records_branches_t_branch_visible_index ON records_branches_t (visible);



