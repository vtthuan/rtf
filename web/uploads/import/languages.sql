insert into languages (code, enabled, modifiedat) values ('fr', 1 , now());
insert into languages (code, enabled, modifiedat) values ('vi', 1 , now());
insert into languages (code, enabled, modifiedat) values ('en', 1 , now());


INSERT INTO classification__collection
(
`name`,
`enabled`,
`slug`,
`created_at`,
`updated_at`)
VALUES
(
'Sport',
1,
'Sport',
now(),
now());