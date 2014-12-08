#
# Table structure for table 'tx_institutioner_institution'
#
CREATE TABLE tx_institutioner_institution (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	iid int(11) DEFAULT '0' NOT NULL,
	hid int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	title_e varchar(255) DEFAULT '' NOT NULL,
	webbadress varchar(255) DEFAULT '' NOT NULL,
	webadress_e varchar(255) DEFAULT '' NOT NULL,
	lucatid varchar(255) DEFAULT '' NOT NULL,
	telefon varchar(255) DEFAULT '' NOT NULL,
	ladok_kod tinytext NOT NULL,
	dont_show tinyint(3) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_institutioner_hus'
#
CREATE TABLE tx_institutioner_hus (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	title_e varchar(255) DEFAULT '' NOT NULL,
	adress varchar(255) DEFAULT '' NOT NULL,
	hs varchar(255) DEFAULT '' NOT NULL,
	webbadress varchar(255) DEFAULT '' NOT NULL,
	webbaddress_e varchar(255) DEFAULT '' NOT NULL,
	husid varchar(255) DEFAULT '' NOT NULL,
	image varchar(255) DEFAULT '' NOT NULL,
	map varchar(255) DEFAULT '' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'fe_groups'
#
CREATE TABLE fe_groups (
    title varchar(255) DEFAULT '' NOT NULL,
    tx_institutioner_lucatid varchar(255) DEFAULT '' NOT NULL,
    tx_institutioner_title_eng varchar(255) DEFAULT '' NOT NULL
);



#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
        roomnumber varchar(255) DEFAULT '',
        registeredaddress int(11) DEFAULT '0',
        street varchar(255) DEFAULT '',
        ou varchar(255) DEFAULT '',
        tx_institutioner_donotdisplayweb varchar(25) DEFAULT '',
	tx_institutioner_lth_search tinyint(3) DEFAULT '0' NOT NULL
);



#
# Table structure for table 'be_groups'
#
CREATE TABLE be_groups (
	tx_institutioner_lucatid varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_institutioner_feuser_description'
#
CREATE TABLE tx_institutioner_feuser_description (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
        username varchar(50) DEFAULT '' NOT NULL,
	description text,
	lang varchar(10) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid)
);