What is QuickQuery ?
===================

Are you looking for a way to query your databases as fast as possible to build quick scripts and small apps? Don't look further, you're at the right place! QuickQuery is a small PHP component that helps doing simple sql queries without the need of typing them, nor generating entities or write a mapping.

This component uses magic methods to build your queries:

    $db->user->asSingleRow(array (
            'username' => 'ninsuo',
    ));

Is the equivalent for:

    SELECT * FROM `user` WHERE `username` = 'ninsuo'

This component was a proof of concept, but I surprised myself taking it from my scratch box each time I needed a to build small scripts requiring database queries. So I decided to clean and share it, enjoy!
