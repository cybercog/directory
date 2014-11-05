
CREATE DATABASE yii_directory CHARACTER SET utf8 COLLATE utf8_general_ci;

USE yii_directory;


CREATE TABLE types_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM ('string', 'text', 'image', 'file') NOT NULL DEFAULT 'string',
    description TEXT DEFAULT NULL,
    validate TEXT DEFAULT NULL,
    UNIQUE INDEX (id),
    UNIQUE INDEX (name),
    INDEX (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE data_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL,
    value VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    validate TEXT DEFAULT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    INDEX (type_id),
    INDEX (value),
    INDEX (visible),
    CONSTRAINT FK_data_t_type_id FOREIGN KEY (type_id) REFERENCES types_t (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE records_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    INDEX (visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE records_data_t
(
    record_id INT NOT NULL,
    data_id INT NOT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    position INT NOT NULL DEFAULT 0,
    sub_position INT NOT NULL DEFAULT 0,
    PRIMARY KEY (record_id, data_id),
    UNIQUE INDEX (record_id, data_id),
    INDEX (data_id),
    INDEX (visible),
    INDEX (position),
    INDEX (sub_position),
    CONSTRAINT FK_records_data_t_record_id FOREIGN KEY (record_id) REFERENCES records_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FK_records_data_t_data_id FOREIGN KEY (data_id) REFERENCES data_t (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE directories_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    UNIQUE INDEX (name),
    INDEX (visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE records_directory_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    directory_id INT NOT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    UNIQUE INDEX (record_id, directory_id),
    INDEX (directory_id),
    INDEX (visible),
    CONSTRAINT FK_records_directory_t_record_id FOREIGN KEY (record_id) REFERENCES records_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FK_records_directory_t_directory_id FOREIGN KEY (directory_id) REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



CREATE TABLE branches_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    INDEX (name),
    INDEX (visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;




CREATE TABLE branches_directories_t
(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    this_branch_id INT NOT NULL,
    parent_branch_id INT DEFAULT NULL,
    directory_id INTEGER NOT NULL REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    UNIQUE INDEX (id),
    UNIQUE INDEX (this_branch_id, parent_branch_id, directory_id),
    INDEX (parent_branch_id),
    INDEX (directory_id),
    INDEX (visible),
    CONSTRAINT FK_branches_directories_t_this_branch_id FOREIGN KEY (this_branch_id) REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FK_branches_directories_t_parent_branch_id FOREIGN KEY (this_branch_id) REFERENCES branches_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FK_branches_directories_t_directory_id FOREIGN KEY (this_branch_id) REFERENCES directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DELIMITER $$
CREATE FUNCTION check_directory_ids(bcid INT, rcid INT) 
RETURNS INT
BEGIN
    RETURN ((SELECT MAX(bdt.directory_id) 
                FROM branches_directories_t bdt 
                WHERE bcid = bdt.id) =  
            (SELECT MAX(rdt.directory_id) 
                FROM records_directory_t rdt 
                WHERE rcid = rdt.id));
END$$
DELIMITER ;



CREATE TABLE records_branches_t
(
    branch_connect_id INT NOT NULL,
    record_connect_id INT NOT NULL,
    visible ENUM ('N', 'Y') NOT NULL DEFAULT 'Y',
    PRIMARY KEY (branch_connect_id, record_connect_id),
    INDEX (record_connect_id),
    INDEX (visible),
    CHECK (  check_directory_ids(branch_connect_id, record_connect_id) ),
    CONSTRAINT FK_records_branches_t_branch_connect_id FOREIGN KEY (branch_connect_id) REFERENCES branches_directories_t (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FK_records_branches_t_record_connect_id FOREIGN KEY (record_connect_id) REFERENCES records_directory_t (id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


