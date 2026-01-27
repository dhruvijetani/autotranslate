CREATE TABLE tx_rdfaq_domain_model_faq (
	title varchar(255) NOT NULL DEFAULT '',
	slug varchar(255) NOT NULL DEFAULT '',
	description text,
	image int(11) unsigned NOT NULL DEFAULT '0',
	sorting int(11) NOT NULL DEFAULT '0',
	categories int(11) unsigned DEFAULT '0'
);

CREATE TABLE tx_rdfaq_domain_model_category (
	title varchar(255) NOT NULL DEFAULT '',
	slug varchar(255) NOT NULL DEFAULT '',
	sorting int(11) NOT NULL DEFAULT '0',
	parentcategory int(11) NOT NULL DEFAULT '0',
);

CREATE TABLE tx_rdfaq_category_mm (
    uid_local int(11) unsigned DEFAULT '0',  
    uid_foreign int(11) unsigned DEFAULT '0',
    tablenames varchar(255) DEFAULT '',      
	fieldname varchar(255) DEFAULT '',      
    sorting int(11) unsigned DEFAULT '0',
    sorting_foreign int(11) unsigned DEFAULT '0'
);