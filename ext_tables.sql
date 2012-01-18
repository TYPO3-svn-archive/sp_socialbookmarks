# ======================================================================
# Table configuration for table "tx_spsocialbookmarks_domain_model_visit"
# ======================================================================
CREATE TABLE tx_spsocialbookmarks_domain_model_visit (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	ip tinytext,
	agent text,
	service tinytext,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
