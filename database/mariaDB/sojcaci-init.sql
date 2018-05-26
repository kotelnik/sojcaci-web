INSERT INTO web_permission (id, name) VALUES (1, 'administration');

INSERT INTO web_user (nick_name, passphrase, is_child) VALUES ('Krolík', '$2y$10$RADxk9wdEYvN5LbQwaxSu.hG36.HPc2MNY7Dq7v08xw4PAJUUVVtS', 0); -- krolik_heslo
INSERT INTO web_user (nick_name, passphrase, is_child) VALUES ('Kotelník', '$2y$10$A08oLR/tMW5HjYWZg8h3QOm.WgwguEEFvCp38Z58ys0mFjzUTxHAy', 0); -- kotelnik_heslo

INSERT INTO web_user_permission (user_id, permission_id) VALUES (1, 1);

INSERT INTO web_user_parent (user_id, parent_id) VALUES (1, 1);

