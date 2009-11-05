#
# Table structure for table 'tx_spsocialbookmarks'
#
CREATE TABLE tx_spsocialbookmarks (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	ip varchar(15) DEFAULT '' NOT NULL,
	agent varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
