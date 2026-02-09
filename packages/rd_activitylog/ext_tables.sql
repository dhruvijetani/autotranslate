CREATE TABLE tx_rdactivitylog_domain_model_log (
	page_uid int(11) NOT NULL DEFAULT '0',
	be_user int(11) NOT NULL DEFAULT '0',
	action_type varchar(255) NOT NULL DEFAULT '',
	user_os varchar(255) DEFAULT '' NOT NULL,
	details text,
);


CREATE TABLE tx_rdactivitylog_domain_model_backendlog (
    uid int(11) NOT NULL auto_increment,
    page_uid int(11) DEFAULT '0' NOT NULL,
    action_type varchar(255) DEFAULT '' NOT NULL,
    user_os varchar(255) DEFAULT '' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    session_id text,
    PRIMARY KEY (uid)
);



CREATE TABLE tx_rdactivitylog_domain_model_sessions (
    user_uid int(11) NOT NULL DEFAULT '0',
    username varchar(255) NOT NULL DEFAULT '',
    session_id text NOT NULL DEFAULT '',
    session_fingerprint varchar(255) NOT NULL DEFAULT '',
    user_agent varchar(255) NOT NULL DEFAULT '',
    is_online int(11) NOT NULL DEFAULT '0',
    is_compromised int(11) NOT NULL DEFAULT '0',
    last_login_time int(11) NOT NULL DEFAULT '0',
    last_activity_time int(11) NOT NULL DEFAULT '0'
);
 
 