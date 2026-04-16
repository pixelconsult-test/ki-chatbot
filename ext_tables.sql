#
# Table structure for table 'tt_content'
# Adds custom fields for the KI Chatbot Widget content element
#
CREATE TABLE tt_content (
    tx_kichatbot_assistant_id varchar(255) DEFAULT '' NOT NULL,
    tx_kichatbot_base_url varchar(255) DEFAULT '' NOT NULL,
    tx_kichatbot_position varchar(10) DEFAULT 'right' NOT NULL,
    tx_kichatbot_theme varchar(10) DEFAULT 'light' NOT NULL,
    tx_kichatbot_auto_open tinyint(1) unsigned DEFAULT 0 NOT NULL,
);
