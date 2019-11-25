--- Password for the admin is "admin" ---
--- Password for any user  is "user"  ---

USE `bread`;

INSERT INTO `users` (`name`,  `email`,
                     `password`,
                     `group`, `status`)

    VALUES ('admin', 'admin@heaven.com',
            '$2y$11$bQCujLEmtKKfCgMhNeH.beh0KADkgPm0SAKOkf1HkZEUoTCgvDqhe',
            'admin', 'enabled'),

           ('John.Smith', 'john.smith@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Jane.Doe', 'jane.doe@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Mike.Rank', 'mike.rank@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('July.Cantrop', 'july.cantrop@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Bob.Sleigh', 'bob.sleigh@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Carla.Byrinth', 'carla.byrinth@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Suzy.Bra', 'suzy.bra@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Luke.E.Lee', 'luke.e.lee@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Mary.Poppins', 'mary.poppins@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Gandalf', 'gandalf@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled'),

           ('Palpatine', 'palpatine@mail.com',
            '$2y$11$k.a6371mZq/tVHAVC0cQVO3bnRaGMwQgW/pwpMZF/fqaReViZPqg2',
            'user', 'enabled');