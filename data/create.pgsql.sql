
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

CREATE VIEW records_tolower_v AS
SELECT r1.id AS id, r1.visible AS visible,
    array_to_string(array(
                            SELECT concat_ws(chr(3), rd2.directory_id, chr(7), rd2.visible, chr(7), d2.visible, chr(7), d2.name, chr(7), d2.description) AS d_info
                            FROM records_directory_t rd2 
                                INNER JOIN directories_t d2 ON d2.id=rd2.directory_id
                            WHERE rd2.record_id=r1.id
                    ), chr(4)) AS directories,
    array_to_string(array(
                            SELECT rd4.directory_id AS d_info
                            FROM records_directory_t rd4 
                            WHERE rd4.record_id=r1.id
                    ), ':') AS directories_id,
    array_to_string(array(
                            SELECT concat_ws(chr(3), rd3.data_id, chr(7), rd3.visible, chr(7), rd3.position, chr(7), rd3.sub_position,
                                    chr(7), t3.type, chr(7), d3.visible, chr(7), d3.value, chr(7), d3.text, chr(7), d3.description,
                                    chr(7), t3.name, chr(7), t3.description) AS r_info
                            FROM records_data_t rd3
                                    INNER JOIN data_t d3 ON d3.id=rd3.data_id
                                    INNER JOIN types_t t3 ON t3.id=d3.type_id
                            WHERE rd3.record_id=r1.id
                    ), chr(4)) AS data,
    array_to_string(array(
                            SELECT concat_ws(chr(3), lower(d5.value), lower(d5.text), lower(d5.description)) AS r_info
                            FROM records_data_t rd5
                                    INNER JOIN data_t d5 ON d5.id=rd5.data_id
                            WHERE rd5.record_id=r1.id
                    ), chr(4)) AS data_lower
FROM records_t r1;

CREATE SEQUENCE hierarhies_t_id_counter CYCLE;
CREATE TABLE hierarhies_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('hierarhies_t_id_counter'),
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    visible visible_type NOT NULL DEFAULT 'Y',
    UNIQUE (name)
);
CREATE INDEX hierarhies_t_id_index ON hierarhies_t (id);
CREATE INDEX hierarhies_t_name_index ON hierarhies_t (name);
CREATE INDEX hierarhies_t_visible_index ON hierarhies_t (visible);

CREATE TABLE hierarhies_directory_t
(
    hierarhy_id INTEGER NOT NULL REFERENCES hierarhies_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    directory_id INTEGER NOT NULL REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (hierarhy_id, directory_id)
);
CREATE INDEX hierarhies_directory_t_record_id_index ON hierarhies_directory_t (hierarhy_id);
CREATE INDEX hierarhies_directory_t_directory_id_index ON hierarhies_directory_t (directory_id);
CREATE INDEX hierarhies_directory_t_visible_index ON hierarhies_directory_t (visible);

CREATE VIEW hierarchies_tolower_v AS
SELECT h1.id AS id,
    lower(h1.name) AS name,
    h1.name AS original_name,
    lower(h1.description) AS description,
    h1.description AS original_description,
    h1.visible AS visible,
    array_to_string(array(
                            SELECT concat_ws(chr(3), hd2.directory_id, chr(7), hd2.visible, chr(7), d2.visible, chr(7), d2.name, chr(7), d2.description) AS d_info
                            FROM hierarhies_directory_t hd2 
                                INNER JOIN directories_t d2 ON d2.id=hd2.directory_id
                            WHERE hd2.hierarhy_id=h1.id
                    ), chr(4)) AS directories,
    array_to_string(array(
                            SELECT hd4.directory_id AS d_info
                            FROM hierarhies_directory_t hd4 
                            WHERE hd4.hierarhy_id=h1.id
                    ), ':') AS directories_id
FROM hierarhies_t h1;


CREATE SEQUENCE branches_t_id_counter CYCLE;
CREATE TABLE branches_t
(
    id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('branches_t_id_counter'),
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL
);
CREATE INDEX branches_t_id_index ON branches_t (id);
CREATE INDEX branches_t_name_index ON branches_t (name);

CREATE TABLE branches_hierarhies_t
(
    branch_root_id INTEGER NOT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    hierarchy_id INTEGER NOT NULL REFERENCES hierarhies_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    position INTEGER DEFAULT 0,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (branch_root_id, hierarhy_id)
);
CREATE INDEX branches_hierarhies_t_branch_root_id_index ON branches_hierarhies_t (branch_root_id);
CREATE INDEX branches_hierarhies_t_hierarhy_id_index ON branches_hierarhies_t (hierarhy_id);
CREATE INDEX branches_hierarhies_t_position_index ON branches_hierarhies_t (position);
CREATE INDEX branches_hierarhies_t_visible_index ON branches_hierarhies_t (visible);

CREATE TABLE branches_branches_t
(
    hierarchy_id INTEGER NOT NULL REFERENCES hierarhies_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    branch_this_id INTEGER NOT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    branch_child_id INTEGER NOT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    position INTEGER DEFAULT 0,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (hierarchy_id, branch_this_id, branch_child_id)
);
CREATE INDEX branches_branches_t_hierarchy_id_index ON branches_branches_t (hierarchy_id);
CREATE INDEX branches_branches_t_branch_this_id_index ON branches_branches_t (branch_this_id);
CREATE INDEX branches_branches_t_branch_child_id_index ON branches_branches_t (branch_child_id);
CREATE INDEX branches_branches_t_position_index ON branches_branches_t (position);
CREATE INDEX branches_branches_t_visible_index ON branches_branches_t (branch_this_id);

CREATE TABLE records_branches_t
(
    hierarchy_id INTEGER NOT NULL REFERENCES hierarhies_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    branch_id INTEGER NOT NULL REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    record_id INTEGER NOT NULL REFERENCES records_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    position INTEGER DEFAULT 0,
    visible visible_type NOT NULL DEFAULT 'Y',
    PRIMARY KEY (hierarchy_id, branch_id, record_id)
);
CREATE INDEX records_branches_t_hierarchy_id_index ON records_branches_t (hierarchy_id);
CREATE INDEX records_branches_t_branch_id_index ON records_branches_t (branch_id);
CREATE INDEX records_branches_t_record_id_index ON records_branches_t (record_id);
CREATE INDEX records_branches_t_position_index ON records_branches_t (position);
CREATE INDEX records_branches_t_visible_index ON records_branches_t (visible);



