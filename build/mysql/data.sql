USE datasec_db;

CREATE TABLE `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO `users` (first_name, last_name, password, email) VALUES
('Kate', 'Winslet', '$2y$10$KCnWnR6bMLDnFxx65lRtgexA6EbeZ.M4yrmiB61yZ8RMVGQTpYQLS', 'kate@wince.com');