CREATE TABLE tx_autotranslate_domain_model_autotranslate (
	page_uid int(11) NOT NULL DEFAULT '0',
	source_lang int(11) NOT NULL DEFAULT '0',
	target_lang int(11) NOT NULL DEFAULT '0',
	records_translated int(11) NOT NULL DEFAULT '0',
	records_originaluid int(11) NOT NULL DEFAULT '0',
	status varchar(255) NOT NULL DEFAULT '',
	message text NOT NULL DEFAULT ''
);
