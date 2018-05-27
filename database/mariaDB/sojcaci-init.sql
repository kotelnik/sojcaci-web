INSERT INTO web_permission (id, name) VALUES (1, 'administration');

INSERT INTO web_user (nick_name, first_name, last_name, passphrase, last_login, notes, is_child) VALUES ('Krolík', 'Hong', 'Zag', '$2y$10$RADxk9wdEYvN5LbQwaxSu.hG36.HPc2MNY7Dq7v08xw4PAJUUVVtS', NOW(), 'alergie na Windows', 0); -- password: "krolik_heslo"
INSERT INTO web_user (nick_name, passphrase, is_child) VALUES ('Kotelník', '$2y$10$A08oLR/tMW5HjYWZg8h3QOm.WgwguEEFvCp38Z58ys0mFjzUTxHAy', 1); -- password: "kotelnik_heslo"

INSERT INTO web_user_permission (user_id, permission_id) VALUES (1, 1);

--
-- news
--
INSERT INTO web_news (description, created, visible_until, author_id) VALUES ('Popis aktuality 1', NOW(), '2019-05-27 12:45:25', 1);
INSERT INTO web_news (description, created, visible_until, author_id) VALUES ('Popis aktuality 2', NOW(), '2018-05-27 12:45:25', 1);
INSERT INTO web_news (description, created, visible_until, author_id) VALUES ('Popis aktuality 3', NOW(), '2018-05-29 12:45:25', 2);
INSERT INTO web_news (description, created, visible_until, author_id) VALUES ('Popis aktuality 4', NOW(), '2018-08-29 12:45:25', 2);