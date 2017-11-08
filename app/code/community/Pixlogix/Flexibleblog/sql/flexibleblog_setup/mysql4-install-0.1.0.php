<?php

$installer = $this;
  
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('flexibleblog_post')};
CREATE TABLE {$this->getTable('flexibleblog_post')} (
    `post_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `post_title` VARCHAR(255) NOT NULL,
    `post_url_key` VARCHAR(255) NOT NULL,
    `post_short_description` text NOT NULL,
    `post_description` text NOT NULL,
    `post_categories` VARCHAR(255) NOT NULL,
    `post_tags` VARCHAR(255) NOT NULL,
    `post_socialshare` INT(2) NOT NULL,
    `post_allow_comments` INT(2) NULL,
    `post_store_view` VARCHAR(12) NOT NULL DEFAULT '0',
    `post_image` VARCHAR(255) NOT NULL,
    `post_author` INT(11) NOT NULL,
    `post_publish_date` DATETIME DEFAULT NULL,
    `post_custom_layout` VARCHAR(100) NOT NULL,
    `post_custom_layout_update` text NOT NULL,
    `post_meta_title` VARCHAR(100) NOT NULL,
    `post_meta_keyword` text NOT NULL,
    `post_meta_description` text NOT NULL,
    `post_status` INT(2) NULL DEFAULT '0',
    `post_created_time` DATETIME DEFAULT NULL,
    `post_update_time` DATETIME DEFAULT NULL,
    PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('flexibleblog_category')};
CREATE TABLE {$this->getTable('flexibleblog_category')} (
    `category_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `category_title` VARCHAR(255) NOT NULL,
    `category_url_key` VARCHAR(255) NOT NULL,
    `category_description` text NOT NULL,
    `category_custom_layout` VARCHAR(100) NOT NULL,
    `category_custom_layout_update` text NOT NULL,
    `category_meta_title` VARCHAR(100) NOT NULL,
    `category_meta_keyword` text NOT NULL,
    `category_meta_description` text NOT NULL,
    `category_status` INT(2) NULL DEFAULT '0',
    `category_created_time` DATETIME DEFAULT NULL,
    `category_update_time` DATETIME DEFAULT NULL,
    PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('flexibleblog_comment')};
CREATE TABLE {$this->getTable('flexibleblog_comment')} (
    `comment_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `comment_parent_id` INT(11) NOT NULL,
    `post_id` INT(11) NOT NULL,
    `comment_name` VARCHAR(255) NOT NULL,
    `comment_email` VARCHAR(255) NOT NULL,
    `comment_website` VARCHAR(255) NOT NULL,
    `comment_description` text NOT NULL,
    `comment_status` INT(2) NULL DEFAULT '0',
    `comment_created_time` DATETIME DEFAULT NULL,
    `comment_update_time` DATETIME DEFAULT NULL,
    `comment_ip` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('flexibleblog_tag')};
CREATE TABLE {$this->getTable('flexibleblog_tag')} (
    `tag_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `tag_name` VARCHAR(255) NOT NULL,
    `tag_url_key` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('flexibleblog_author')};
CREATE TABLE {$this->getTable('flexibleblog_author')} (
    `author_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `author_url_key` VARCHAR(255) NOT NULL,
    `author_name` VARCHAR(255) NOT NULL,
    `author_avatar` varchar(255) NULL,
    `author_bio` text NULL,
    `author_created_time` DATETIME DEFAULT NULL,
    `author_update_time` DATETIME DEFAULT NULL,
    PRIMARY KEY (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT into {$this->getTable('flexibleblog_author')}
    (author_url_key,author_name,author_created_time,author_update_time) 
    SELECT username,firstname,NOW(),NOW() FROM admin_user LIMIT 0,1
");

$installer->endSetup();