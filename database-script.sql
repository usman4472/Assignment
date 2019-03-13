
CREATE TABLE IF NOT EXISTS `tbl_contact` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `tbl_contact` (`id`, `first_name`, `last_name`, `address`, `email`, `phone`, `date_of_birth`) VALUES
(1, 'Peter', 'Parker', 'Queens', 'peter@parker.com', '4455664455', '1990-04-10'),
(2, 'Barry', 'Allen', 'Florida', 'barry@allen.com', '2211335566', '1983-02-02'),
(3, 'Bruce', 'Banner', 'Newyork', 'bruce@banner.com', '7788995566', '1987-04-14'),
(4, 'Bruce', 'Wayne', 'Gotham', 'bruce@wayne.com', '8877887744', '1991-11-15'),
(5, 'Harvy', 'Dent', 'Newyork', 'harvy@dent.com', '9988774445', '1990-10-01'),
(6, 'Tony', 'Stark', 'Texas', 'tony@stark.com', '8899886655', '1984-10-05'),
(7, 'Nick', 'Fury', 'Olympia', 'nick@fury.com', '9966554488', '1980-01-25'),
(8, 'John', 'Mclane', 'orlando', 'john@maclay.com', '7744114411', '2000-11-15'),
(9, 'Howard', 'Roark', 'Newyork', 'howard@roark.com', '8745554413', '2011-11-15'),
(10, 'Peter', 'Keating', 'Texas', 'peter@keating.com', '9089094445', '2013-11-15'),
(11, 'Dominique', 'Francon', 'Gotham', 'dominique@francon.com', '9890124418', '2011-01-01'),
(12, 'Ellsworth', 'Toohey', 'Texas', 'ellsworth@toohey.com', '7678123331', '1990-10-01'),
(13, 'Catherine', 'Halsey', 'Olympia', 'catherine@halsey.com', '8990453211', '1990-02-02');
